<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('users')
            ->result_array();
    }


    public function get_all_with_position() {
        return $this->db
            ->select('id, username, email, full_name, position, role, employee_id')
            ->order_by('full_name', 'ASC')
            ->get('users')
            ->result_array();
    }

    public function get_by_id($id) {
        return $this->db->get_where('users', array('id' => $id))->row_array();
    }

    public function get_by_username($username) {
        return $this->db->get_where('users', array('username' => $username))->row_array();
    }

    public function get_by_email($email) {
        return $this->db->get_where('users', array('email' => $email))->row_array();
    }


    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('users') > 0;
    }


    public function email_exists($email, $exclude_id = null) {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        return $this->db->count_all_results('users') > 0;
    }


    public function count_all() {
        return $this->db->count_all('users');
    }


    public function count_by_role($role) {
        return $this->db
            ->where('role', $role)
            ->count_all_results('users');
    }


    public function get_by_role($role) {
        return $this->db
            ->where('role', $role)
            ->order_by('full_name', 'ASC')
            ->get('users')
            ->result_array();
    }

    public function insert($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }


    public function generate_employee_id($role) {
        $date_prefix = date('ymd'); 
        
        $role_code = ($role === 'admin') ? '11' : '12';
        
        $this->db->select('employee_id');
        $this->db->like('employee_id', $date_prefix . $role_code, 'after');
        $this->db->where('role', $role);
        $this->db->order_by('employee_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            $last_id = $query->row()->employee_id;
            $last_number = intval(substr($last_id, -3));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        $employee_id = $date_prefix . $role_code . str_pad($new_number, 3, '0', STR_PAD_LEFT);
        
        return $employee_id;
    }


    public function get_next_employee_id($role) {
        return $this->generate_employee_id($role);
    }
}