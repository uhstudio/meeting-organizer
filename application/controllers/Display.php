<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Display extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Meeting_model');
        $this->load->model('Meeting_room_model');
    }


    public function index() {

        $data = array(
            'title' => 'Meeting Room Displays - PT TJB Power Services', 
            'rooms' => $this->Meeting_room_model->get_all_active()
        );

        $this->load->view('display/index', $data);
    }

    public function room($room_id) {

        $room = $this->Meeting_room_model->get_by_id($room_id);
        
        if (!$room) {
            show_404();
            return;
        }

        $data = array(
            'room' => $room,
            'room_id' => $room_id
        );

        $this->load->view('display/room', $data);
    }

    public function api_meeting_status($room_id) {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $all_meetings = $this->get_today_meetings($room_id, $today);
        
        $current_meeting = null;
        $upcoming_meetings = [];
        
        foreach ($all_meetings as $meeting) {
            $start_time = $meeting['start_time'];
            $end_time = $meeting['end_time'];
            
            if ($current_time >= $start_time && $current_time < $end_time) {
                $current_meeting = $this->format_meeting_data($meeting);
            }
            elseif ($current_time < $start_time) {
                $upcoming_meetings[] = $this->format_meeting_data($meeting);
            }
        }
        
        $upcoming_meetings = array_slice($upcoming_meetings, 0, 5);

        $status = 'available';
        if ($current_meeting) {
            $status = 'ongoing';
        } elseif (count($all_meetings) > 0) {
            $status = 'no_meeting';
        }
        
        $response = array(
            'success' => true,
            'current_time' => $current_time,
            'current_date' => $today,
            'status' => $status,
            'current_meeting' => $current_meeting,
            'upcoming_meetings' => $upcoming_meetings,
            'total_meetings_today' => count($all_meetings)
        );

        echo json_encode($response);
    }

    private function get_today_meetings($room_id, $date) {

        $this->db->select('meetings.*, users.full_name as requester_name, users.position as requester_position, meeting_platforms.platform_name');
        $this->db->from('meetings');

        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');

        $this->db->where('meetings.room_id', $room_id);
        $this->db->where('meetings.meeting_date', $date);
        $this->db->where('meetings.status !=', 'cancelled');

        $this->db->order_by('meetings.start_time', 'ASC');
        
        $query = $this->db->get();

        return $query->result_array();
    }

    private function format_meeting_data($meeting) {

        return array(
            'id' => $meeting['id'],
            'topic' => $meeting['topic'],
            'description' => $meeting['description'],
            'meeting_type' => $meeting['meeting_type'],
            'start_time' => substr($meeting['start_time'], 0, 5),
            'end_time' => substr($meeting['end_time'], 0, 5),
            'start_time_full' => $meeting['start_time'],
            'end_time_full' => $meeting['end_time'],
            'requester_name' => $meeting['requester_name'],
            'requester_position' => $meeting['requester_position'],
            'platform_name' => $meeting['platform_name'],
            'duration' => $meeting['duration']
        );
    }

    public function api_room_meetings($room_id) {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $today = date('Y-m-d');

        $meetings = $this->get_today_meetings($room_id, $today);
        
        $formatted_meetings = array();
        foreach ($meetings as $meeting) {
            $formatted_meetings[] = $this->format_meeting_data($meeting);
        }
        
        $response = array(
            'success' => true,
            'date' => $today,
            'room_id' => $room_id,
            'meetings' => $formatted_meetings,
            'total' => count($formatted_meetings)
        );

        echo json_encode($response);
    }

    public function api_current_meeting($room_id) {

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        
        $this->db->select('meetings.*, users.full_name as requester_name, users.position as requester_position, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->where('meetings.room_id', $room_id);
        $this->db->where('meetings.meeting_date', $today);
        $this->db->where('meetings.start_time <=', $current_time);
        $this->db->where('meetings.end_time >', $current_time);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->order_by('meetings.start_time', 'ASC');
        $this->db->limit(1);
        
        $current_meeting = $this->db->get()->row_array();
        
        $this->db->select('meetings.*, users.full_name as requester_name, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->where('meetings.room_id', $room_id);
        $this->db->where('meetings.meeting_date', $today);
        $this->db->where('meetings.start_time >', $current_time);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->order_by('meetings.start_time', 'ASC');
        $this->db->limit(1);
        
        $next_meeting = $this->db->get()->row_array();
        
        $response = array(
            'success' => true,
            'current_meeting' => $current_meeting,
            'next_meeting' => $next_meeting,
            'status' => $current_meeting ? 'occupied' : 'available',
            'current_time' => $current_time
        );

        echo json_encode($response);
    }
}
