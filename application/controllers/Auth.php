<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 

        $this->load->model('User_model');      
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    public function login()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->User_model->get_by_username($username);

        if ($user) {
            if (password_verify($password, $user['password'])) {

                $session_data = [
                    'user_id'      => $user['id'],
                    'username'     => $user['username'],
                    'email'        => $user['email'],
                    'full_name'    => $user['full_name'],
                    'employee_id'  => $user['employee_id'],
                    'position'     => $user['position'],
                    'role'         => $user['role'],
                    'logged_in'    => TRUE
                ];

                $this->session->set_userdata($session_data);

                redirect('dashboard');

            } else {
                $this->session->set_flashdata('error', 'Password salah');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();

        redirect('auth');
    }
}
