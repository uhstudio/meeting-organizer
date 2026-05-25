<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PHPMailer_lib
{
    public function __construct()
    {
        log_message('debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        require_once FCPATH . 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        return $mail;
    }
}