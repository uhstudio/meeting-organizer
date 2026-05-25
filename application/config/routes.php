<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['auth'] = 'auth/index';
$route['auth/login'] = 'auth/login';
$route['auth/logout'] = 'auth/logout';

// Dashboard routes
$route['dashboard'] = 'dashboard/index';
$route['dashboard/calendar'] = 'dashboard/calendar';
$route['dashboard/room-schedule'] = 'dashboard/room_schedule';
$route['dashboard/room-schedule/(:num)'] = 'dashboard/room_schedule/$1';

// API Routes
$route['api/rooms'] = 'dashboard/get_rooms_api';
$route['api/rooms/(:num)/meetings'] = 'dashboard/get_room_meetings_api/$1';

// Meeting routes - ORDER MATTERS! Specific routes must come before general ones
$route['meeting/create'] = 'meeting/create';
$route['meeting/store'] = 'meeting/store';
$route['meeting/detail/(:num)'] = 'meeting/detail/$1'; // CRITICAL: Must be before edit
$route['meeting/edit/(:num)'] = 'meeting/edit/$1';
$route['meeting/update/(:num)'] = 'meeting/update/$1';
$route['meeting/delete/(:num)'] = 'meeting/delete/$1';
$route['meeting/change-status/(:num)'] = 'meeting/change_status/$1';
$route['meeting/check-availability'] = 'meeting/check_availability';
$route['meeting'] = 'meeting/index';
$route['meeting/check-availability'] = 'meeting/check_availability';

// Admin routes - Manage Users
$route['admin/users'] = 'admin/users';
$route['admin/users/create'] = 'admin/users_create';
$route['admin/users/store'] = 'admin/users_store';
$route['admin/users/edit/(:num)'] = 'admin/users_edit/$1';
$route['admin/users/update/(:num)'] = 'admin/users_update/$1';
$route['admin/users/delete/(:num)'] = 'admin/users_delete/$1';
$route['admin/users/get-next-employee-id'] = 'admin/get_next_employee_id';

// Display routes - Meeting Room Displays
$route['display'] = 'display/index';
$route['display/room/(:num)'] = 'display/room/$1';
$route['display/api_room_meetings/(:num)'] = 'display/api_room_meetings/$1';
$route['display/api_current_meeting/(:num)'] = 'display/api_current_meeting/$1';