<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_check {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function check_login() {
        $controller = strtolower($this->CI->router->fetch_class());
        $method = strtolower($this->CI->router->fetch_method());
        
        $public_controllers = array('auth', 'display'); 
        
        if (in_array($controller, $public_controllers)) {
            return TRUE;
        }
        
        if (!$this->CI->session->userdata('logged_in')) {
            $this->CI->session->set_userdata('redirect_url', current_url());
            
            redirect('auth');
        }
        
        return TRUE;
    }

    
    public function check_role($required_role) {
        $user_role = $this->CI->session->userdata('role');
        
        if ($user_role !== $required_role) {
            $this->CI->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
            return FALSE;
        }
        
        return TRUE;
    }
    

    public function is_admin() {
        return $this->CI->session->userdata('role') === 'admin';
    }
    

    public function get_user_data() {
        if ($this->CI->session->userdata('logged_in')) {
            return array(
                'user_id' => $this->CI->session->userdata('user_id'),
                'username' => $this->CI->session->userdata('username'),
                'email' => $this->CI->session->userdata('email'),
                'full_name' => $this->CI->session->userdata('full_name'),
                'role' => $this->CI->session->userdata('role')
            );
        }
        
        return NULL;
    }
}

?>