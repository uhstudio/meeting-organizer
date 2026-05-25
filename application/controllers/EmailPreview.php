<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailPreview extends CI_Controller {

    public function meeting()
    {
        // ===== PREVIEW =====
        $meeting = [
            'topic' => 'Tes Preview Email Meeting',
            'meeting_type' => 'online',
            'meeting_date' => '2026-01-30',
            'start_time' => '13:30:00',
            'end_time' => '14:00:00',
            'duration' => 30,
            'requester_name' => 'Umar Hadi Wijaya',
            'room_name' => 'Meeting Room Admin I',
            'platform_name' => 'Zoom',
            'meeting_link' => 'https://zoom.us/j/123456789',
            'passcode' => '123456',
            'description' => "Ini hanya preview tampilan email.\nSilakan edit CSS dengan nyaman 😎",
            'attendance_link' => 'http://localhost/meeting-app/attendance/test'
        ];

        $this->load->view('emails/meeting_invitation', [
            'meeting' => $meeting
        ]);
    }
}
