<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Meeting extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Meeting_model');
        $this->load->model('Meeting_room_model');
        $this->load->model('Meeting_platform_model');
        $this->load->model('Meeting_participant_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('Email_helper'); 
    }

    public function index() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $this->Meeting_model->auto_update_status();
        $this->auto_update_meeting_status();

        $user_id = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');

        // ========== GET FILTER PARAMETERS ==========
        $filter_month = $this->input->get('month'); 
        $filter_type = $this->input->get('type'); 
        $filter_status = $this->input->get('status'); 

        // ========== GET MEETINGS WITH FILTERS ==========
        if ($user_role === 'admin') {
            $meetings = $this->Meeting_model->get_all_meetings_with_details($filter_month, $filter_type, $filter_status);
        } else {
            $meetings = $this->Meeting_model->get_user_all_meetings($user_id, $filter_month, $filter_type, $filter_status);
        }

        // ========== HITUNG COUNTER DENGAN LOGIKA BARU ==========
        $total_meetings = count(array_filter($meetings, function($m) { 
            return $m['status'] === 'completed'; 
        }));
        
        $scheduled = count(array_filter($meetings, function($m) { 
            return $m['status'] === 'scheduled'; 
        }));
        
        $ongoing = count(array_filter($meetings, function($m) { 
            return $m['status'] === 'ongoing'; 
        }));
        
        $online_only = count(array_filter($meetings, function($m) { 
            return $m['meeting_type'] === 'online'; 
        }));
        
        $offline_only = count(array_filter($meetings, function($m) { 
            return $m['meeting_type'] === 'offline'; 
        }));
        
        $hybrid = count(array_filter($meetings, function($m) { 
            return $m['meeting_type'] === 'hybrid'; 
        }));
        
        $online = $online_only;
        $offline = $offline_only;
        // ========== END HITUNG COUNTER ==========

        $data = array(
            'title' => 'All Meetings',
            'meetings' => $meetings,
            'total_meetings' => $total_meetings,
            'scheduled' => $scheduled,
            'ongoing' => $ongoing,
            'online' => $online,
            'offline' => $offline,
            'hybrid' => $hybrid,
            'filter_month' => $filter_month,
            'filter_type' => $filter_type,
            'filter_status' => $filter_status
        );

        $this->load->view('meeting/index', $data);
    }

    private function auto_update_meeting_status() {
        $now = date('Y-m-d H:i:s');
        
        $this->db->where('status', 'scheduled');
        $this->db->where("CONCAT(meeting_date, ' ', start_time) <=", $now);
        $this->db->where("CONCAT(meeting_date, ' ', end_time) >", $now);
        $this->db->update('meetings', array('status' => 'ongoing'));
        
        $this->db->where('status', 'ongoing');
        $this->db->where("CONCAT(meeting_date, ' ', end_time) <=", $now);
        $this->db->update('meetings', array('status' => 'completed'));
    }

    public function detail($id) {
        if (!$this->session->userdata('logged_in')) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized access']);
            return;
        }

        $meeting = $this->Meeting_model->get_meeting_detail($id);
        
        if (!$meeting) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Meeting not found']);
            return;
        }

        $participants = $this->Meeting_model->get_meeting_participants($id);
        
        $response = array(
            'id' => $meeting['id'],
            'meeting_code' => $meeting['meeting_code'],
            'topic' => $meeting['topic'],
            'description' => $meeting['description'],
            'meeting_type' => $meeting['meeting_type'],
            'status' => $meeting['status'],
            'meeting_date' => $meeting['meeting_date'],
            'start_time' => $meeting['start_time'],
            'end_time' => $meeting['end_time'],
            'duration' => $meeting['duration'],
            'formatted_date' => date('l, d F Y', strtotime($meeting['meeting_date'])),
            'formatted_start_time' => date('H:i', strtotime($meeting['start_time'])),
            'formatted_end_time' => date('H:i', strtotime($meeting['end_time'])),
            'formatted_created_at' => date('d M Y, H:i', strtotime($meeting['created_at'])),
            'formatted_updated_at' => date('d M Y, H:i', strtotime($meeting['updated_at'])),
            'room_name' => $meeting['room_name'],
            'capacity' => $meeting['capacity'],
            'platform_name' => $meeting['platform_name'],
            'meeting_link' => $meeting['meeting_link'],
            'passcode' => $meeting['passcode'],
            'requester_name' => $meeting['requester_name'],
            'requester_email' => $meeting['requester_email'],
            'requester_position' => $meeting['requester_position'],
            'participants' => $participants
        );

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function create() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can create meetings');
            redirect('dashboard');
        }

        $data = array(
            'title' => 'Create Meeting',
            'rooms' => $this->Meeting_room_model->get_all_active(),
            'platforms_grouped' => $this->Meeting_platform_model->get_all_active_grouped(),
            'users' => $this->User_model->get_all_with_position()
        );

        $this->load->view('meeting/create', $data);
    }

    public function store() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can create meetings');
            redirect('dashboard');
        }

        $this->form_validation->set_rules('topic', 'Topic', 'required|min_length[3]');
        $this->form_validation->set_rules('meeting_type', 'Meeting Type', 'required|in_list[online,offline,hybrid]');
        $this->form_validation->set_rules('meeting_date', 'Date', 'required');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required');
        $this->form_validation->set_rules('duration', 'Duration', 'required|numeric');
        $this->form_validation->set_rules('requested_by', 'Requested By', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('errors', explode("\n", trim($errors)));
            redirect('meeting/create');
        }

        $meeting_type = $this->input->post('meeting_type');
        $start_time = $this->input->post('start_time');
        $duration = $this->input->post('duration');
        $meeting_date = $this->input->post('meeting_date');
        
        $end_time = date('H:i:s', strtotime($start_time . " + $duration minutes"));

        // ========== VALIDASI TIDAK BOLEH BUAT MEETING DI WAKTU LAMPAU ==========
        $now = date('Y-m-d H:i:s');

        $meeting_start = $meeting_date . ' ' . $start_time;
        if (strlen($start_time) == 5) {
            $meeting_start .= ':00';
        }

        if (strtotime($meeting_start) <= strtotime($now)) {
            $this->session->set_flashdata('error', 
                'You cannot schedule a meeting in the past. Current server time: ' . $now
            );
            redirect('meeting/create');
            return;
        }
        // ========== END VALIDASI ==========

        // ========== VALIDASI ROOM CONFLICT ==========
        if ($meeting_type === 'offline' || $meeting_type === 'hybrid') {
            $room_id = $this->input->post('room_id');
            
            if (!$room_id) {
                $this->session->set_flashdata('error', 'Please select a meeting room');
                redirect('meeting/create');
            }
            
            $conflicts = $this->Meeting_model->check_room_availability(
                $room_id,
                $meeting_date,
                $start_time,
                $end_time
            );
            
            if ($conflicts) {
                $conflict_msg = $this->Meeting_model->get_conflict_message($conflicts);
                $this->session->set_flashdata('error', $conflict_msg);
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/create');
            }
        }
        // ========== END VALIDASI ROOM ==========

        // ========== VALIDASI PLATFORM CONFLICT ==========
        if ($meeting_type === 'online' || $meeting_type === 'hybrid') {
            $platform_id = $this->input->post('platform_id');
            
            if (!$platform_id) {
                $this->session->set_flashdata('error', 'Please select a platform');
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/create');
            }
            
            $conflicts = $this->Meeting_model->check_platform_availability(
                $platform_id,
                $meeting_date,
                $start_time,
                $end_time
            );
            
            if ($conflicts) {
                $conflict_msg = $this->Meeting_model->get_platform_conflict_message($conflicts);
                $this->session->set_flashdata('error', $conflict_msg);
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/create');
            }
        }
        // ========== END VALIDASI PLATFORM ==========

        $meeting_code = $this->generate_meeting_code();

        $meeting_data = array(
            'meeting_code' => $meeting_code,
            'topic' => $this->input->post('topic'),
            'description' => $this->input->post('description'),
            'meeting_type' => $meeting_type,
            'meeting_date' => $meeting_date,
            'start_time' => $start_time,
            'duration' => $duration,
            'end_time' => $end_time,
            'is_recurring' => $this->input->post('is_recurring') ? 1 : 0,
            'requested_by' => $this->input->post('requested_by')
        );

        if ($meeting_type === 'offline' || $meeting_type === 'hybrid') {
            $meeting_data['room_id'] = $this->input->post('room_id');
        }

        if ($meeting_type === 'online' || $meeting_type === 'hybrid') {
            $meeting_data['platform_id'] = $this->input->post('platform_id');
            $meeting_data['meeting_link'] = $this->input->post('meeting_link');
            $meeting_data['passcode'] = $this->input->post('passcode');
        }

        $meeting_id = $this->Meeting_model->insert($meeting_data);

        if ($meeting_id) {
            $participants = $this->input->post('participants');
            
            if ($participants && is_array($participants)) {
                foreach ($participants as $user_id) {
                    $this->Meeting_participant_model->insert(array(
                        'meeting_id' => $meeting_id,
                        'user_id' => $user_id
                    ));
                }
                
                // ========== SEND EMAIL INVITATIONS ==========
                try {
                    log_message('info', 'Attempting to send email invitations for meeting ID: ' . $meeting_id);
                    
                    $email_result = $this->email_helper->send_meeting_invitation($meeting_id, $participants);
                    
                    log_message('info', 'Email result: ' . json_encode($email_result));
                    
                    if ($email_result['success'] > 0) {
                        $success_msg = 'Meeting created successfully! ';
                        $success_msg .= 'Calendar invitations sent to ' . $email_result['success'] . ' participant(s).';
                        
                        if ($email_result['failed'] > 0) {
                            $success_msg .= ' Failed to send to ' . $email_result['failed'] . ' email(s).';
                        }
                        
                        $this->session->set_flashdata('success', $success_msg);
                    } else {
                        $this->session->set_flashdata('success', 'Meeting created successfully.');
                        $this->session->set_flashdata('warning', 
                            'Failed to send email invitations. Please check email configuration or send manually.');
                    }
                    
                } catch (Exception $e) {
                    log_message('error', 'Email sending exception: ' . $e->getMessage());
                    $this->session->set_flashdata('success', 'Meeting created successfully.');
                    $this->session->set_flashdata('warning', 
                        'Email notifications could not be sent: ' . $e->getMessage());
                }
                // ========== END SEND EMAIL ==========
                
            } else {
                $this->session->set_flashdata('success', 'Meeting created successfully.');
            }

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Failed to create meeting');
            redirect('meeting/create');
        }
    }

    public function edit($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can edit meetings');
            redirect('dashboard');
        }

        $meeting = $this->Meeting_model->get_by_id($id);
        
        if (!$meeting) {
            show_404();
        }

        $participants = $this->Meeting_participant_model->get_by_meeting($id);
        $participant_ids = array();
        foreach ($participants as $p) {
            $participant_ids[] = $p['user_id'];
        }

        $data = array(
            'title' => 'Edit Meeting',
            'meeting' => $meeting,
            'rooms' => $this->Meeting_room_model->get_all_active(),
            'platforms_grouped' => $this->Meeting_platform_model->get_all_active_grouped(),
            'users' => $this->User_model->get_all_with_position(),
            'participantIds' => $participant_ids
        );

        $this->load->view('meeting/edit', $data);
    }

    public function update($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can update meetings');
            redirect('dashboard');
        }

        $meeting = $this->Meeting_model->get_by_id($id);
        
        if (!$meeting) {
            show_404();
        }

        $this->form_validation->set_rules('topic', 'Topic', 'required|min_length[3]');
        $this->form_validation->set_rules('meeting_type', 'Meeting Type', 'required|in_list[online,offline,hybrid]');
        $this->form_validation->set_rules('meeting_date', 'Date', 'required');
        $this->form_validation->set_rules('start_time', 'Start Time', 'required');
        $this->form_validation->set_rules('duration', 'Duration', 'required|numeric');
        $this->form_validation->set_rules('requested_by', 'Requested By', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('errors', explode("\n", trim($errors)));
            redirect('meeting/edit/' . $id);
        }

        $meeting_type = $this->input->post('meeting_type');
        $start_time = $this->input->post('start_time');
        $duration = $this->input->post('duration');
        $meeting_date = $this->input->post('meeting_date');
        
        $end_time = date('H:i:s', strtotime($start_time . " + $duration minutes"));

        // ========== VALIDASI TIDAK BOLEH BUAT MEETING DI WAKTU LAMPAU ==========
        $now = date('Y-m-d H:i:s');

        $meeting_start = $meeting_date . ' ' . $start_time;
        if (strlen($start_time) == 5) {
            $meeting_start .= ':00';
        }

        if (strtotime($meeting_start) <= strtotime($now)) {
            $this->session->set_flashdata('error', 
                'You cannot schedule a meeting in the past. Current server time: ' . $now
            );
            redirect('meeting/edit/' . $id);
            return;
        }
        // ========== END VALIDASI ==========

        // ========== VALIDASI ROOM CONFLICT ==========
        if ($meeting_type === 'offline' || $meeting_type === 'hybrid') {
            $room_id = $this->input->post('room_id');
            
            if (!$room_id) {
                $this->session->set_flashdata('error', 'Please select a meeting room');
                redirect('meeting/edit/' . $id);
            }
            
            $conflicts = $this->Meeting_model->check_room_availability(
                $room_id,
                $meeting_date,
                $start_time,
                $end_time,
                $id
            );
            
            if ($conflicts) {
                $conflict_msg = $this->Meeting_model->get_conflict_message($conflicts);
                $this->session->set_flashdata('error', $conflict_msg);
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/edit/' . $id);
            }
        }
        // ========== END VALIDASI ROOM ==========

        // ========== VALIDASI PLATFORM CONFLICT ==========
        if ($meeting_type === 'online' || $meeting_type === 'hybrid') {
            $platform_id = $this->input->post('platform_id');
            
            if (!$platform_id) {
                $this->session->set_flashdata('error', 'Please select a platform');
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/edit/' . $id);
            }
            
            $conflicts = $this->Meeting_model->check_platform_availability(
                $platform_id,
                $meeting_date,
                $start_time,
                $end_time,
                $id
            );
            
            if ($conflicts) {
                $conflict_msg = $this->Meeting_model->get_platform_conflict_message($conflicts);
                $this->session->set_flashdata('error', $conflict_msg);
                $this->session->set_flashdata('form_data', $this->input->post());
                redirect('meeting/edit/' . $id);
            }
        }
        // ========== END VALIDASI PLATFORM ==========

        // ========== PREPARE MEETING DATA ==========
        $meeting_data = array(
            'topic' => $this->input->post('topic'),
            'description' => $this->input->post('description'),
            'meeting_type' => $meeting_type,
            'meeting_date' => $meeting_date,
            'start_time' => $start_time,
            'duration' => $duration,
            'end_time' => $end_time,
            'is_recurring' => $this->input->post('is_recurring') ? 1 : 0,
            'requested_by' => $this->input->post('requested_by'),
            'room_id' => NULL,
            'platform_id' => NULL,
            'meeting_link' => NULL,
            'passcode' => NULL
        );

        if ($meeting_type === 'offline' || $meeting_type === 'hybrid') {
            $room_id = $this->input->post('room_id');
            $meeting_data['room_id'] = !empty($room_id) ? (int)$room_id : NULL;
        }

        if ($meeting_type === 'online' || $meeting_type === 'hybrid') {
            $platform_id = $this->input->post('platform_id');
            $meeting_link = $this->input->post('meeting_link');
            $passcode = $this->input->post('passcode');
            
            $meeting_data['platform_id'] = !empty($platform_id) ? (int)$platform_id : NULL;
            $meeting_data['meeting_link'] = !empty($meeting_link) ? $meeting_link : NULL;
            $meeting_data['passcode'] = !empty($passcode) ? $passcode : NULL;
        }
        // ========== END PREPARE DATA ==========

        if ($this->Meeting_model->update($id, $meeting_data)) {
            // ========== GET OLD & NEW PARTICIPANTS ==========
            $old_participants = $this->Meeting_participant_model->get_by_meeting($id);
            $old_participant_ids = array_column($old_participants, 'user_id');
            
            $this->Meeting_participant_model->delete_by_meeting($id);
            
            $participants = $this->input->post('participants');
            $new_participant_ids = [];
            
            if ($participants && is_array($participants)) {
                foreach ($participants as $user_id) {
                    $this->Meeting_participant_model->insert(array(
                        'meeting_id' => $id,
                        'user_id' => $user_id
                    ));
                }
                $new_participant_ids = $participants;
                
                // ========== SEND EMAIL TO NEW PARTICIPANTS ONLY ==========
                $added_participants = array_diff($new_participant_ids, $old_participant_ids);
                
                if (!empty($added_participants)) {
                    try {
                        log_message('info', 'Sending emails to new participants for meeting ID: ' . $id);
                        
                        $email_result = $this->email_helper->send_meeting_invitation($id, $added_participants);
                        
                        log_message('info', 'Email result: ' . json_encode($email_result));
                        
                        if ($email_result['success'] > 0) {
                            $success_msg = 'Meeting updated successfully! ';
                            $success_msg .= 'Calendar invitations sent to ' . $email_result['success'] . ' new participant(s).';
                            
                            if ($email_result['failed'] > 0) {
                                $success_msg .= ' Failed to send to ' . $email_result['failed'] . ' email(s).';
                            }
                            
                            $this->session->set_flashdata('success', $success_msg);
                        } else {
                            $this->session->set_flashdata('success', 'Meeting updated successfully.');
                            $this->session->set_flashdata('warning', 'Failed to send email invitations to new participants.');
                        }
                        
                    } catch (Exception $e) {
                        log_message('error', 'Email sending exception: ' . $e->getMessage());
                        $this->session->set_flashdata('success', 'Meeting updated successfully.');
                        $this->session->set_flashdata('warning', 
                            'Email notifications could not be sent: ' . $e->getMessage());
                    }
                } else {
                    $this->session->set_flashdata('success', 'Meeting updated successfully.');
                }
                // ========== END SEND EMAIL ==========
                
            } else {
                $this->session->set_flashdata('success', 'Meeting updated successfully.');
            }

            redirect('meeting');
        } else {
            $this->session->set_flashdata('error', 'Failed to update meeting');
            redirect('meeting/edit/' . $id);
        }
    }

    public function check_availability() {
        header('Content-Type: application/json');
        
        $room_id = $this->input->post('room_id');
        $meeting_date = $this->input->post('meeting_date');
        $start_time = $this->input->post('start_time');
        $duration = $this->input->post('duration');
        $exclude_id = $this->input->post('exclude_id');
        
        if (!$room_id || !$meeting_date || !$start_time || !$duration) {
            echo json_encode([
                'available' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }
        
        $end_time = date('H:i:s', strtotime($start_time . " + $duration minutes"));
        
        $conflicts = $this->Meeting_model->check_room_availability(
            $room_id,
            $meeting_date,
            $start_time,
            $end_time,
            $exclude_id
        );
        
        if ($conflicts) {
            echo json_encode([
                'available' => false,
                'message' => $this->Meeting_model->get_conflict_message($conflicts),
                'conflicts' => $conflicts
            ]);
        } else {
            echo json_encode([
                'available' => true,
                'message' => 'Room is available'
            ]);
        }
    }

    public function check_platform_availability() {
        header('Content-Type: application/json');
        
        $platform_id = $this->input->post('platform_id');
        $meeting_date = $this->input->post('meeting_date');
        $start_time = $this->input->post('start_time');
        $duration = $this->input->post('duration');
        $exclude_id = $this->input->post('exclude_id');
        
        if (!$platform_id || !$meeting_date || !$start_time || !$duration) {
            echo json_encode([
                'available' => false,
                'message' => 'Missing required parameters'
            ]);
            return;
        }
        
        $end_time = date('H:i:s', strtotime($start_time . " + $duration minutes"));
        
        $conflicts = $this->Meeting_model->check_platform_availability(
            $platform_id,
            $meeting_date,
            $start_time,
            $end_time,
            $exclude_id
        );
        
        if ($conflicts) {
            echo json_encode([
                'available' => false,
                'message' => $this->Meeting_model->get_platform_conflict_message($conflicts),
                'conflicts' => $conflicts
            ]);
        } else {
            echo json_encode([
                'available' => true,
                'message' => 'Platform is available'
            ]);
        }
    }

    public function delete($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can delete meetings');
            redirect('dashboard');
        }

        $meeting = $this->Meeting_model->get_by_id($id);
        
        if (!$meeting) {
            show_404();
        }

        if ($this->Meeting_model->delete($id)) {
            $this->session->set_flashdata('success', 'Meeting deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete meeting');
        }

        redirect('dashboard');
    }

    public function change_status($id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can change meeting status');
            redirect('dashboard');
        }

        $meeting = $this->Meeting_model->get_by_id($id);
        
        if (!$meeting) {
            show_404();
        }

        $new_status = $this->input->post('status');
        $valid_statuses = array('scheduled', 'ongoing', 'completed', 'cancelled');

        if (!in_array($new_status, $valid_statuses)) {
            $this->session->set_flashdata('error', 'Invalid status');
            redirect('meeting');
        }

        if ($this->Meeting_model->update($id, array('status' => $new_status))) {
            $this->session->set_flashdata('success', 'Meeting status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update status');
        }

        redirect('meeting');
    }

    private function generate_meeting_code() {
        $part1 = rand(100, 999);
        $part2 = rand(1000, 9999);
        $part3 = rand(1000, 9999);
        
        return "$part1.$part2.$part3";
    }

    public function auto_update_status()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->where('status', 'scheduled');
        $this->db->where("CONCAT(meeting_date, ' ', start_time) <=", $now);
        $this->db->where("CONCAT(meeting_date, ' ', end_time) >", $now);
        $this->db->update('meetings', ['status' => 'ongoing']);

        $this->db->where('status', 'ongoing');
        $this->db->where("CONCAT(meeting_date, ' ', end_time) <=", $now);
        $this->db->update('meetings', ['status' => 'completed']);
    }
}