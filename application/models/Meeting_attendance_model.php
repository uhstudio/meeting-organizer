<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Meeting_attendance_model extends CI_Model {

    protected $table = 'meeting_attendance';

    public function __construct() {
        parent::__construct();
    }


    public function create_attendance($data) {
        $data['marked_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'present';
        
        return $this->db->insert($this->table, $data);
    }


    public function check_duplicate($meeting_id, $email) {
        return $this->db
            ->where('meeting_id', $meeting_id)
            ->where('email', $email)
            ->get($this->table)
            ->row_array();
    }


    public function get_by_meeting($meeting_id) {
        return $this->db
            ->select('
                meeting_attendance.*,
                meetings.topic,
                meetings.meeting_date,
                meetings.start_time,
                meetings.end_time
            ')
            ->from($this->table)
            ->join('meetings', 'meetings.id = meeting_attendance.meeting_id')
            ->where('meeting_attendance.meeting_id', $meeting_id)
            ->order_by('meeting_attendance.marked_at', 'DESC')
            ->get()
            ->result_array();
    }


    public function get_meeting_statistics($meeting_id) {
        $total = $this->db
            ->where('meeting_id', $meeting_id)
            ->count_all_results($this->table);
        
        $present = $this->db
            ->where(['meeting_id' => $meeting_id, 'status' => 'present'])
            ->count_all_results($this->table);

        return [
            'total' => $total,
            'present' => $present,
            'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0
        ];
    }


    public function get_by_id($id) {
        return $this->db
            ->select('
                meeting_attendance.*,
                meetings.topic,
                meetings.meeting_date,
                meetings.start_time,
                meetings.end_time,
                meetings.meeting_type
            ')
            ->from($this->table)
            ->join('meetings', 'meetings.id = meeting_attendance.meeting_id')
            ->where('meeting_attendance.id', $id)
            ->get()
            ->row_array();
    }


    public function delete_by_meeting($meeting_id) {
        return $this->db->delete($this->table, ['meeting_id' => $meeting_id]);
    }


    public function count_by_meeting($meeting_id) {
        return $this->db
            ->where('meeting_id', $meeting_id)
            ->count_all_results($this->table);
    }
}