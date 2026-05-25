<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'] = array(
    'class'    => 'Auth_check',
    'function' => 'check_login',
    'filename' => 'Auth_check.php',
    'filepath' => 'hooks'
);