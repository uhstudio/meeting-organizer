<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-01-21 10:14:13 --> Severity: Notice --> Undefined variable: platforms C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
ERROR - 2026-01-21 10:14:13 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
ERROR - 2026-01-21 11:32:33 --> Severity: Notice --> Undefined variable: platforms C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
ERROR - 2026-01-21 11:32:33 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
ERROR - 2026-01-21 11:32:39 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`meeting_schedule`.`meetings`, CONSTRAINT `meetings_ibfk_2` FOREIGN KEY (`platform_id`) REFERENCES `meeting_platforms` (`id`) ON DELETE SET NULL) - Invalid query: UPDATE `meetings` SET `topic` = 'test room', `description` = 'NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN', `meeting_type` = 'hybrid', `meeting_date` = '2026-01-21', `start_time` = '11:33', `duration` = '30', `end_time` = '12:03:00', `is_recurring` = 0, `requested_by` = '35', `room_id` = '1', `platform_id` = '', `meeting_link` = 'https://zoom.com', `passcode` = NULL
WHERE `id` = '70'
ERROR - 2026-01-21 11:37:13 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\meeting-app\application\controllers\Meeting.php 334
ERROR - 2026-01-21 11:37:50 --> Severity: Notice --> Undefined variable: platforms C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
ERROR - 2026-01-21 11:37:50 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\meeting-app\application\views\meeting\edit.php 440
