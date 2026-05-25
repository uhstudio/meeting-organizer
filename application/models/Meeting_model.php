<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('meetings')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('meetings', array('id' => $id))->row_array();
    }

    public function insert($data) {
        $this->db->insert('meetings', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('meetings', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('meetings');
    }

    public function get_today_meetings($date) {
        $this->db->select('meetings.*, users.full_name as requester_name, meeting_rooms.room_name, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->where('meetings.meeting_date', $date);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->order_by('meetings.start_time', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_user_meetings($user_id, $date) {
        $this->db->select('meetings.*, users.full_name as requester_name, meeting_rooms.room_name, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->join('meeting_participants', 'meeting_participants.meeting_id = meetings.id', 'left');
        $this->db->group_start();
        $this->db->where('meetings.requested_by', $user_id);
        $this->db->or_where('meeting_participants.user_id', $user_id);
        $this->db->group_end();
        $this->db->where('meetings.meeting_date', $date);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->group_by('meetings.id');
        $this->db->order_by('meetings.start_time', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_month_meetings($year, $month) {
        $this->db->select('meetings.*, users.full_name as requester_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->where('YEAR(meetings.meeting_date)', $year);
        $this->db->where('MONTH(meetings.meeting_date)', $month);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->order_by('meetings.meeting_date', 'ASC');
        $this->db->order_by('meetings.start_time', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_user_month_meetings($user_id, $year, $month) {
        $this->db->select('meetings.*, users.full_name as requester_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_participants', 'meeting_participants.meeting_id = meetings.id', 'left');
        $this->db->group_start();
        $this->db->where('meetings.requested_by', $user_id);
        $this->db->or_where('meeting_participants.user_id', $user_id);
        $this->db->group_end();
        $this->db->where('YEAR(meetings.meeting_date)', $year);
        $this->db->where('MONTH(meetings.meeting_date)', $month);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->group_by('meetings.id');
        $this->db->order_by('meetings.meeting_date', 'ASC');
        $this->db->order_by('meetings.start_time', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_room_meetings($room_id, $date) {
        $this->db->select('meetings.*, users.full_name as requester_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->where('meetings.room_id', $room_id);
        $this->db->where('meetings.meeting_date', $date);
        $this->db->where('meetings.status !=', 'cancelled');
        $this->db->order_by('meetings.start_time', 'ASC');
        return $this->db->get()->result_array();
    }


    public function get_all_meetings_with_details($filter_month = null, $filter_type = null, $filter_status = null) {
        $this->db->select('meetings.*, users.full_name as requester_name, meeting_rooms.room_name, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        
        if ($filter_month) {
            $parts = explode('-', $filter_month);
            if (count($parts) == 2) {
                $year = $parts[0];
                $month = $parts[1];
                $this->db->where('YEAR(meetings.meeting_date)', $year);
                $this->db->where('MONTH(meetings.meeting_date)', $month);
            }
        }
        
        if ($filter_type && in_array($filter_type, ['online', 'offline', 'hybrid'])) {
            $this->db->where('meetings.meeting_type', $filter_type);
        }
        
        if ($filter_status && in_array($filter_status, ['scheduled', 'ongoing', 'completed', 'cancelled'])) {
            $this->db->where('meetings.status', $filter_status);
        }
        
        $this->db->order_by('meetings.meeting_date', 'DESC');
        $this->db->order_by('meetings.start_time', 'DESC');
        return $this->db->get()->result_array();
    }


    public function get_user_all_meetings($user_id, $filter_month = null, $filter_type = null, $filter_status = null) {
        $this->db->select('meetings.*, users.full_name as requester_name, meeting_rooms.room_name, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->join('meeting_participants', 'meeting_participants.meeting_id = meetings.id', 'left');
        $this->db->group_start();
        $this->db->where('meetings.requested_by', $user_id);
        $this->db->or_where('meeting_participants.user_id', $user_id);
        $this->db->group_end();
        
        if ($filter_month) {
            $parts = explode('-', $filter_month);
            if (count($parts) == 2) {
                $year = $parts[0];
                $month = $parts[1];
                $this->db->where('YEAR(meetings.meeting_date)', $year);
                $this->db->where('MONTH(meetings.meeting_date)', $month);
            }
        }
        
        if ($filter_type && in_array($filter_type, ['online', 'offline', 'hybrid'])) {
            $this->db->where('meetings.meeting_type', $filter_type);
        }
        
        if ($filter_status && in_array($filter_status, ['scheduled', 'ongoing', 'completed', 'cancelled'])) {
            $this->db->where('meetings.status', $filter_status);
        }
        
        $this->db->group_by('meetings.id');
        $this->db->order_by('meetings.meeting_date', 'DESC');
        $this->db->order_by('meetings.start_time', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_today_meetings_grouped_by_room($date) {
        $this->db->select('room_id, topic');
        $this->db->from('meetings');
        $this->db->where('meeting_date', $date);
        $this->db->where('status !=', 'cancelled');
        $this->db->where('room_id IS NOT NULL');
        $this->db->order_by('start_time', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_meeting_detail($id) {
        $this->db->select('meetings.*, users.full_name as requester_name, users.email as requester_email, users.position as requester_position, meeting_rooms.room_name, meeting_rooms.capacity, meeting_platforms.platform_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left');
        $this->db->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left');
        $this->db->where('meetings.id', $id);
        return $this->db->get()->row_array();
    }

    public function get_meeting_participants($meeting_id) {
        $this->db->select('users.id, users.full_name, users.email, users.position, users.employee_id, meeting_participants.attendance_status');
        $this->db->from('meeting_participants');
        $this->db->join('users', 'users.id = meeting_participants.user_id', 'left');
        $this->db->where('meeting_participants.meeting_id', $meeting_id);
        $this->db->order_by('users.full_name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function check_room_availability($room_id, $meeting_date, $start_time, $end_time, $exclude_meeting_id = null) {
        $this->db->select('meetings.*, users.full_name as requester_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        $this->db->where('meetings.room_id', $room_id);
        $this->db->where('meetings.meeting_date', $meeting_date);
        $this->db->where('meetings.status !=', 'cancelled');
        
        if ($exclude_meeting_id) {
            $this->db->where('meetings.id !=', $exclude_meeting_id);
        }
        
        $this->db->group_start();
        $this->db->where("'$start_time' < meetings.end_time");
        $this->db->where("'$end_time' > meetings.start_time");
        $this->db->group_end();
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array(); 
        }
        
        return null; 
    }


    public function get_conflict_message($conflicts) {
        if (empty($conflicts)) {
            return '';
        }
        
        $messages = [];
        foreach ($conflicts as $conflict) {
            $start = date('H:i', strtotime($conflict['start_time']));
            $end = date('H:i', strtotime($conflict['end_time']));
            $requester = $conflict['requester_name'] ?: 'Unknown';
            
            $messages[] = sprintf(
                '"%s" (%s - %s) requested by %s',
                $conflict['topic'],
                $start,
                $end,
                $requester
            );
        }
        
        return 'Room is already booked: ' . implode('; ', $messages);
    }


    public function check_platform_availability($platform_id, $meeting_date, $start_time, $end_time, $exclude_meeting_id = null) {
        $this->db->select('meetings.*, users.full_name as requester_name');
        $this->db->from('meetings');
        $this->db->join('users', 'users.id = meetings.requested_by', 'left');
        
        $this->db->where('meetings.platform_id', $platform_id);
        
        $this->db->where('meetings.meeting_date', $meeting_date);
        
        $this->db->where('meetings.status !=', 'cancelled');
        

        if ($exclude_meeting_id) {
            $this->db->where('meetings.id !=', $exclude_meeting_id);
        }
        

        $this->db->group_start();
        $this->db->where("'$start_time' < meetings.end_time");
        $this->db->where("'$end_time' > meetings.start_time");
        $this->db->group_end();
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        
        return null;
    }


    public function get_platform_conflict_message($conflicts) {
        if (empty($conflicts)) {
            return '';
        }
        
        $messages = [];
        
        foreach ($conflicts as $conflict) {
            $start = date('H:i', strtotime($conflict['start_time']));
            
            $end = date('H:i', strtotime($conflict['end_time']));
            
            $requester = $conflict['requester_name'] ?: 'Unknown';
            

            $messages[] = sprintf(
                '"%s" (%s - %s) requested by %s',
                $conflict['topic'],
                $start,
                $end,
                $requester
            );
        }
        

        return 'Platform is already booked: ' . implode('; ', $messages);
    }

    // ================= AUTO UPDATE MEETING STATUS =================
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