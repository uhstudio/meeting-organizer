<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_room_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('meeting_rooms')->result_array();
    }

    public function get_all_active() {
        $this->db->where('status', 'active');
        return $this->db->get('meeting_rooms')->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('meeting_rooms', array('id' => $id))->row_array();
    }

    public function insert($data) {
        $this->db->insert('meeting_rooms', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('meeting_rooms', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('meeting_rooms');
    }

    public function get_with_activities() {
        $this->db->select('meeting_rooms.*, GROUP_CONCAT(room_activities.activity_name ORDER BY room_activities.display_order SEPARATOR ", ") as activities');
        $this->db->from('meeting_rooms');
        $this->db->join('room_activities', 'room_activities.room_id = meeting_rooms.id', 'left');
        $this->db->group_by('meeting_rooms.id');
        return $this->db->get()->result_array();
    }
}