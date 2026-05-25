<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_activity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('room_activities')->result_array();
    }

    public function get_by_room($room_id) {
        $this->db->where('room_id', $room_id);
        $this->db->order_by('display_order', 'ASC');
        return $this->db->get('room_activities')->result_array();
    }

    public function insert($data) {
        $this->db->insert('room_activities', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('room_activities', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('room_activities');
    }
}