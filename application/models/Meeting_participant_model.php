<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_participant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('meeting_participants')->result_array();
    }

    public function get_by_meeting($meeting_id) {
        $this->db->where('meeting_id', $meeting_id);
        return $this->db->get('meeting_participants')->result_array();
    }

    public function get_participants($meeting_id) {
        $this->db->select('meeting_participants.*, users.full_name, users.email');
        $this->db->from('meeting_participants');
        $this->db->join('users', 'users.id = meeting_participants.user_id');
        $this->db->where('meeting_id', $meeting_id);
        return $this->db->get()->result_array();
    }

    public function insert($data) {
        $this->db->insert('meeting_participants', $data);
        return $this->db->insert_id();
    }

    public function delete_by_meeting($meeting_id) {
        $this->db->where('meeting_id', $meeting_id);
        return $this->db->delete('meeting_participants');
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('meeting_participants');
    }
}