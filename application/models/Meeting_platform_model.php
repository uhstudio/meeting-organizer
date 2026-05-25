<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_platform_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get('meeting_platforms')->result_array();
    }

    public function get_all_active() {
        $this->db->where('status', 'active');
        $this->db->order_by('platform_name', 'ASC');
        return $this->db->get('meeting_platforms')->result_array();
    }

    public function get_all_active_grouped() {
        $this->db->where('status', 'active');
        $this->db->order_by('platform_type', 'ASC');
        $this->db->order_by('platform_name', 'ASC');
        $platforms = $this->db->get('meeting_platforms')->result_array();
        
        $grouped = array();
        foreach ($platforms as $platform) {
            $type = isset($platform['platform_type']) ? $platform['platform_type'] : 'Other';
            if (!isset($grouped[$type])) {
                $grouped[$type] = array();
            }
            $grouped[$type][] = $platform;
        }
        
        return $grouped;
    }

    public function get_by_id($id) {
        return $this->db->get_where('meeting_platforms', array('id' => $id))->row_array();
    }

    public function insert($data) {
        $this->db->insert('meeting_platforms', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('meeting_platforms', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('meeting_platforms');
    }
}