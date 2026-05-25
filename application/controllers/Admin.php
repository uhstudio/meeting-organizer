<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        
        $this->load->model('User_model');           
        $this->load->library('form_validation');   
        $this->load->library('session');           
        $this->load->helper(['url', 'form']);      
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Access denied. Admin only.');
            redirect('dashboard');
        }
    }

    public function users() {
        $data = [
            'title' => 'Manage Users',
            'users' => $this->User_model->get_all(),
            'total_users' => $this->User_model->count_all(),
            'total_admins' => $this->User_model->count_by_role('admin'),
            'total_regular_users' => $this->User_model->count_by_role('user') 
        ];
        
        $this->load->view('admin/users', $data);
    }

    public function users_create() {
        $data = [
            'title' => 'Create New User', 
            'next_admin_id' => $this->User_model->get_next_employee_id('admin'),
            'next_user_id' => $this->User_model->get_next_employee_id('user') 
        ];
        
        $this->load->view('admin/users_create', $data);
    }

    public function get_next_employee_id() {
        $role = $this->input->post('role');
        
        if (!in_array($role, ['admin', 'user'])) {
            echo json_encode(['error' => 'Invalid role']); 
            return;
        }
        
        $next_id = $this->User_model->get_next_employee_id($role);

        echo json_encode(['employee_id' => $next_id]);
    }

    public function users_store() {
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|min_length[3]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|matches[password]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,user]');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('errors', explode("\n", trim($errors)));
            
            $this->session->set_flashdata('old_input', [
                'employee_id' => $this->input->post('employee_id'),
                'position' => $this->input->post('position'),
                'full_name' => $this->input->post('full_name'),
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            ]);
            
            redirect('admin/users/create');
        }
        
        $role = $this->input->post('role');
        
        $employee_id = $this->input->post('employee_id');
        if (empty($employee_id)) {
            $employee_id = $this->User_model->generate_employee_id($role);
        }
        
        $user_data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'full_name' => $this->input->post('full_name'),
            'employee_id' => $employee_id,
            'position' => $this->input->post('position'),
            'role' => $role,
            'password' => $this->input->post('password') 
        ];
        
        if ($this->User_model->insert($user_data)) {
            $this->session->set_flashdata('success', 'User created successfully with Employee ID: ' . $employee_id);
            redirect('admin/users');
        } else {
            $this->session->set_flashdata('error', 'Failed to create user');
            redirect('admin/users/create');
        }
    }

    // form edit user
    public function users_edit($id) {
        $user = $this->User_model->get_by_id($id);
        
        if (!$user) {
            show_404();
        }
        
        if ($user['id'] == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot edit your own account from here');
            redirect('admin/users');
        }
        
        $data = [
            'title' => 'Edit User',
            'user' => $user
        ];
        
        $this->load->view('admin/users_edit', $data);
    }

    // update data user
    public function users_update($id) {
        $user = $this->User_model->get_by_id($id);
        
        if (!$user) {
            show_404();
        }
        
        if ($user['id'] == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot edit your own account');
            redirect('admin/users');
        }
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|min_length[3]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,user]');
        
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'matches[password]');
        }
        
        if ($this->input->post('email') !== $user['email']) {
            if ($this->User_model->email_exists($this->input->post('email'), $id)) {
                $this->session->set_flashdata('error', 'Email already exists');
                
                $this->session->set_flashdata('old_input', [
                    'employee_id' => $this->input->post('employee_id'),
                    'position' => $this->input->post('position'),
                    'full_name' => $this->input->post('full_name'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role')
                ]);
                
                redirect('admin/users/edit/' . $id);
                return;
            }
        }
        
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('errors', explode("\n", trim($errors)));
            
            $this->session->set_flashdata('old_input', [
                'employee_id' => $this->input->post('employee_id'),
                'position' => $this->input->post('position'),
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            ]);
            
            redirect('admin/users/edit/' . $id);
        }
        
        $user_data = [
            'email' => $this->input->post('email'),
            'full_name' => $this->input->post('full_name'),
            'employee_id' => $this->input->post('employee_id'),
            'position' => $this->input->post('position'),
            'role' => $this->input->post('role')
        ];
        
        if ($this->input->post('password')) {
            $user_data['password'] = $this->input->post('password');
        }
        
        if ($this->User_model->update($id, $user_data)) {
            $this->session->set_flashdata('success', 'User updated successfully');
            redirect('admin/users');
        } else {
            $this->session->set_flashdata('error', 'Failed to update user');
            redirect('admin/users/edit/' . $id);
        }
    }

    public function users_delete($id) {
        $user = $this->User_model->get_by_id($id);
        
        if (!$user) {
            show_404();
        }
        
        if ($user['id'] == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account');
            redirect('admin/users');
            return;
        }
        
        $this->db->where('requested_by', $id);
        $meeting_count = $this->db->count_all_results('meetings');
        
        if ($meeting_count > 0) {
            $this->session->set_flashdata('error', 'Cannot delete user. User has ' . $meeting_count . ' meeting(s) associated.');
            redirect('admin/users');
            return;
        }
        
        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user');
        }
        
        redirect('admin/users');
    }
}
