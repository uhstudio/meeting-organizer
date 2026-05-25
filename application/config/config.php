<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');


$config['base_url'] = 'http://localhost/meeting-app/';
$config['index_page'] = '';
$config['uri_protocol'] = 'AUTO';
$config['encryption_key'] = '9c8e1c69153c0a1a9d1a14ca73da6ddd';

$config['sess_driver'] = 'files';
$config['sess_save_path'] = sys_get_temp_dir();
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_regenerate_destroy'] = FALSE;

$config['log_threshold'] = 1;
$config['log_path'] = '';
$config['subclass_prefix'] = 'MY_';

$config['enable_query_strings'] = FALSE;
$config['allow_get_array'] = TRUE;

$config['enable_hooks'] = TRUE;

