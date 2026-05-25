<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 

        $this->load->model('Meeting_model');        
        $this->load->model('Meeting_room_model');   
        $this->load->library('session');            
        $this->load->helper('url');                 
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth'); 
        }

        $this->auto_update_meeting_status();

        $today = date('Y-m-d');

        $user_id   = $this->session->userdata('user_id'); 
        $user_role = $this->session->userdata('role');    
        
        if ($user_role === 'admin') {
            $meetings = $this->Meeting_model->get_today_meetings($today);
        } else {
            $meetings = $this->Meeting_model->get_user_meetings($user_id, $today);
        }

        $data = [
            'title'        => 'Meeting Organizer', 
            'meetings'     => $meetings,           
            'current_date' => $today,             
            'user_role'    => $user_role           
        ];

        $this->load->view('dashboard/index', $data);
    }

    private function auto_update_meeting_status()
    {
        $this->Meeting_model->auto_update_status();
    }

    public function calendar()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth'); 
        }

        $month = $this->input->get('month') ?? date('m');
        $year  = $this->input->get('year')  ?? date('Y');

        $user_id   = $this->session->userdata('user_id');
        $user_role = $this->session->userdata('role');

        if ($user_role === 'admin') {
            $meetings = $this->Meeting_model->get_month_meetings($year, $month);
        } else {
            $meetings = $this->Meeting_model->get_user_month_meetings($user_id, $year, $month);
        }

        $data = [
            'title'    => 'Calendar', 
            'meetings' => $meetings, 
            'month'    => $month,     
            'year'     => $year       
        ];

        $this->load->view('dashboard/calendar', $data);
    }

    public function room_schedule($room_id = NULL)
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        $rooms = $this->Meeting_room_model->get_all_active();

        $today = date('Y-m-d');

        $today_meetings = $this->Meeting_model
            ->get_today_meetings_grouped_by_room($today);

        $room_meeting_map = [];
        foreach ($today_meetings as $meeting) {
            $room_meeting_map[$meeting['room_id']][] = $meeting['topic'];
        }

        $selected_room = NULL;            
        $meetings      = [];              
        $display_date  = date('Y-m-d');   

        if ($room_id) {
            $selected_room = $this->Meeting_room_model->get_by_id($room_id);

            $display_date  = $this->input->get('date') ?? date('Y-m-d');

            $meetings = $this->Meeting_model
                ->get_room_meetings($room_id, $display_date);
        }

        $data = [
            'title'           => 'Room Schedule',      
            'rooms'           => $rooms,               
            'selectedRoom'    => $selected_room,       
            'meetings'        => $meetings,            
            'roomMeetingMap'  => $room_meeting_map,    
            'displayDate'     => $display_date         
        ];

        $this->load->view('dashboard/room_schedule', $data);
    }

    public function get_rooms_api()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['error' => 'Unauthorized']); 
            return;
        }

        $rooms = $this->Meeting_room_model->get_all_active();

        $today = date('Y-m-d');
        $today_meetings = $this->Meeting_model
            ->get_today_meetings_grouped_by_room($today);

        $room_meeting_map = [];
        foreach ($today_meetings as $meeting) {
            $room_meeting_map[$meeting['room_id']][] = $meeting['topic'];
        }

        foreach ($rooms as &$room) {
            $room['meetings'] = $room_meeting_map[$room['id']] ?? [];
        }

        echo json_encode($rooms);
    }

    public function get_room_meetings_api($room_id)
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $date = $this->input->get('date') ?? date('Y-m-d');

        $meetings = $this->Meeting_model
            ->get_room_meetings($room_id, $date);

        echo json_encode($meetings);
    }

    public function auto_update_api()
    {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $this->Meeting_model->auto_update_status();

        echo json_encode(['success' => true]);
    }
}
