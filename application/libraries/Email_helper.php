<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email_helper {

    protected $CI;
    protected $config;

    public function __construct() {
        $this->CI =& get_instance();
        
        $this->CI->load->config('phpmailer', TRUE);
        $this->config = $this->CI->config->item('phpmailer');
        
        $this->CI->load->library('PHPMailer_lib');
    }

    public function send_meeting_invitation($meeting_id, $participant_ids) {
        
        $meeting = $this->get_meeting_details($meeting_id);
        if (!$meeting) {
            log_message('error', 'Meeting not found: ' . $meeting_id);
            return false;
        }

        $sent_count = 0;
        $failed_emails = [];

        $requester = $this->CI->User_model->get_by_id($meeting['requested_by']);
        $is_requester_also_participant = in_array($meeting['requested_by'], $participant_ids);
        
        if ($requester && !empty($requester['email']) && !$is_requester_also_participant) {
            sleep(1); 
            if ($this->send_email_to_organizer($requester, $meeting)) {
                $sent_count++;
                log_message('info', 'Calendar invite sent to organizer: ' . $requester['email']);
            } else {
                $failed_emails[] = $requester['email'];
                log_message('error', 'Failed to send calendar invite to organizer: ' . $requester['email']);
            }
        }

        foreach ($participant_ids as $user_id) {
            sleep(1); 
            $user = $this->CI->User_model->get_by_id($user_id);
            if (!$user || empty($user['email'])) {
                log_message('warning', 'User not found or no email: ' . $user_id);
                continue;
            }

            $is_organizer = ($user_id == $meeting['requested_by']);
            
            if ($this->send_email_to_participant($user, $meeting, $is_organizer)) {
                $sent_count++;
            } else {
                $failed_emails[] = $user['email'];
            }
        }

        return [
            'success' => $sent_count,
            'failed'  => count($failed_emails),
            'failed_emails' => $failed_emails
        ];
    }

    private function send_email_to_organizer($user, $meeting)
    {
        try {
            $mail = $this->CI->phpmailer_lib->load();

            // Server settings
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['smtp_user'];
            $mail->Password   = $this->config['smtp_pass'];
            $mail->SMTPSecure = $this->config['smtp_crypto'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->Timeout    = $this->config['smtp_timeout'];

            // Recipients
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($user['email'], $user['full_name']);
            $mail->addReplyTo($this->config['from_email'], $this->config['from_name']);

            // Content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Meeting Created: ' . $meeting['topic'];

            // Build attendance link
            $meeting['attendance_link'] = base_url('attendance/mark/' . $meeting['id']);

            // Build email body with Google Calendar URL
            $meeting['google_calendar_url'] = $this->build_google_calendar_url($meeting);
            $meeting['is_organizer'] = true;
            
            $htmlBody = $this->CI->load->view(
                'emails/meeting_invitation',
                ['meeting' => $meeting],
                TRUE
            );
            
            $mail->Body = $htmlBody;

            $ics_content = $this->build_calendar_ics_publish($meeting, $user);

            $mail->addStringAttachment(
                $ics_content,
                'meeting.ics',
                'base64',
                'text/calendar; method=PUBLISH; charset=UTF-8'
            );

            // Send email
            $result = $mail->send();

            if ($result) {
                log_message('info', 'Email sent successfully to organizer: ' . $user['email']);
                return true;
            }

            log_message('error', 'Email send failed for organizer: ' . $user['email']);
            return false;

        } catch (Exception $e) {
            log_message('error', 'PHPMailer Exception (Organizer): ' . $e->getMessage());
            return false;
        } catch (Throwable $e) {
            log_message('error', 'Email exception (Organizer): ' . $e->getMessage());
            return false;
        }
    }


    private function send_email_to_participant($user, $meeting, $is_organizer = false)
    {
        try {
            // Create PHPMailer instance
            $mail = $this->CI->phpmailer_lib->load();

            // Server settings
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['smtp_user'];
            $mail->Password   = $this->config['smtp_pass'];
            $mail->SMTPSecure = $this->config['smtp_crypto'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->Timeout    = $this->config['smtp_timeout'];

            // Recipients
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($user['email'], $user['full_name']);
            $mail->addReplyTo($this->config['from_email'], $this->config['from_name']);

            // Content
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            
            if ($is_organizer) {
                $mail->Subject = 'Meeting Created: ' . $meeting['topic'];
            } else {
                $mail->Subject = 'Meeting Invitation: ' . $meeting['topic'];
            }

            $meeting['attendance_link'] = base_url('attendance/mark/' . $meeting['id']);

            $meeting['google_calendar_url'] = $this->build_google_calendar_url($meeting);
            $meeting['is_organizer'] = $is_organizer;
            
            $htmlBody = $this->CI->load->view(
                'emails/meeting_invitation',
                ['meeting' => $meeting],
                TRUE
            );
            
            $mail->Body = $htmlBody;

            if ($is_organizer) {
                $ics_content = $this->build_calendar_ics_publish($meeting, $user);
                $method = 'PUBLISH';
            } else {
                $ics_content = $this->build_calendar_ics($meeting, $user);
                $method = 'REQUEST';
            }

            $mail->addStringAttachment(
                $ics_content,
                'meeting.ics',
                'base64',
                'text/calendar; method=' . $method . '; charset=UTF-8'
            );

            $result = $mail->send();

            if ($result) {
                log_message('info', 'Email sent successfully to: ' . $user['email']);
                return true;
            }

            log_message('error', 'Email send failed for: ' . $user['email']);
            return false;

        } catch (Exception $e) {
            log_message('error', 'PHPMailer Exception: ' . $e->getMessage());
            return false;
        } catch (Throwable $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }

    private function build_google_calendar_url($meeting) {
        $start = $meeting['meeting_date'] . ' ' . $meeting['start_time'];
        $end   = $meeting['meeting_date'] . ' ' . $meeting['end_time'];

        $startDT = new DateTime($start, new DateTimeZone('Asia/Jakarta'));
        $endDT   = new DateTime($end, new DateTimeZone('Asia/Jakarta'));

        $dtStart = $startDT->format('Ymd\THis');
        $dtEnd   = $endDT->format('Ymd\THis');

        $details = "Meeting Details:\n\n";
        $details .= "Organizer: " . $meeting['requester_name'] . "\n";
        $details .= "Duration: " . $meeting['duration'] . " minutes\n\n";
        
        if (!empty($meeting['meeting_link'])) {
            $details .= "Join Meeting: " . $meeting['meeting_link'] . "\n";
        }
        
        if (!empty($meeting['passcode'])) {
            $details .= "Passcode: " . $meeting['passcode'] . "\n";
        }
        
        if (!empty($meeting['description'])) {
            $details .= "\nAgenda:\n" . $meeting['description'];
        }

        if ($meeting['meeting_type'] === 'offline') {
            $location = $meeting['room_name'] ?? 'TBD';
        } elseif ($meeting['meeting_type'] === 'online') {
            $location = 'Online Meeting';
        } else {
            $location = ($meeting['room_name'] ?? 'TBD') . ' / Online';
        }

        $params = [
            'action' => 'TEMPLATE',
            'text' => $meeting['topic'],
            'dates' => $dtStart . '/' . $dtEnd,
            'details' => $details,
            'location' => $location,
            'ctz' => 'Asia/Jakarta'
        ];

        return 'https://calendar.google.com/calendar/render?' . http_build_query($params);
    }

    private function build_calendar_ics_publish($meeting, $user)
    {
        $start = $meeting['meeting_date'] . ' ' . $meeting['start_time'];
        $end   = $meeting['meeting_date'] . ' ' . $meeting['end_time'];

        $startDT = new DateTime($start, new DateTimeZone('Asia/Jakarta'));
        $endDT   = new DateTime($end, new DateTimeZone('Asia/Jakarta'));

        $startDT->setTimezone(new DateTimeZone('UTC'));
        $endDT->setTimezone(new DateTimeZone('UTC'));

        $dtStart = $startDT->format('Ymd\THis\Z');
        $dtEnd   = $endDT->format('Ymd\THis\Z');
        $dtStamp = gmdate('Ymd\THis\Z');

        if ($meeting['meeting_type'] === 'offline') {
            $location = $meeting['room_name'] ?? 'TBD';
        } elseif ($meeting['meeting_type'] === 'online') {
            $location = 'Online Meeting';
        } else {
            $location = ($meeting['room_name'] ?? 'TBD') . ' / Online';
        }

        $description = "MEETING DETAILS\\n\\n";
        $description .= "Topic: " . $this->ics_escape($meeting['topic']) . "\\n";
        $description .= "Organizer: " . $this->ics_escape($meeting['requester_name']) . "\\n";
        $description .= "Time: " . date('H:i', strtotime($meeting['start_time'])) . " - " . date('H:i', strtotime($meeting['end_time'])) . " WIB\\n";
        $description .= "Duration: " . $meeting['duration'] . " minutes\\n\\n";

        if (!empty($meeting['meeting_link'])) {
            $description .= "Join: " . $meeting['meeting_link'] . "\\n";
        }

        if (!empty($meeting['passcode'])) {
            $description .= "Passcode: " . $meeting['passcode'] . "\\n";
        }

        if (!empty($meeting['description'])) {
            $description .= "\\nAgenda:\\n" . $this->ics_escape($meeting['description']);
        }

        $uid = 'meeting-' . $meeting['id'] . '@tjbps.com';

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//PT TJB Power Services//Meeting System v1.0//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";

        $ics .= "BEGIN:VTIMEZONE\r\n";
        $ics .= "TZID:Asia/Jakarta\r\n";
        $ics .= "BEGIN:STANDARD\r\n";
        $ics .= "DTSTART:19700101T000000\r\n";
        $ics .= "TZOFFSETFROM:+0700\r\n";
        $ics .= "TZOFFSETTO:+0700\r\n";
        $ics .= "END:STANDARD\r\n";
        $ics .= "END:VTIMEZONE\r\n";

        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . $uid . "\r\n";
        $ics .= "DTSTAMP:" . $dtStamp . "\r\n";
        $ics .= "DTSTART:" . $dtStart . "\r\n";
        $ics .= "DTEND:" . $dtEnd . "\r\n";
        $ics .= "SUMMARY:" . $this->ics_escape($meeting['topic']) . "\r\n";
        $ics .= "DESCRIPTION:" . $description . "\r\n";
        $ics .= "LOCATION:" . $this->ics_escape($location) . "\r\n";
        
        $organizer_email = !empty($meeting['requester_email']) 
            ? $meeting['requester_email'] 
            : $this->config['from_email'];
        $ics .= "ORGANIZER;CN=\"" . $this->ics_escape($meeting['requester_name']) . "\":MAILTO:" . $organizer_email . "\r\n";
        
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "SEQUENCE:0\r\n";
        $ics .= "PRIORITY:5\r\n";
        $ics .= "TRANSP:OPAQUE\r\n";

        $ics .= "BEGIN:VALARM\r\n";
        $ics .= "TRIGGER:-PT15M\r\n";
        $ics .= "ACTION:DISPLAY\r\n";
        $ics .= "DESCRIPTION:Meeting Reminder\r\n";
        $ics .= "END:VALARM\r\n";

        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }


    private function build_calendar_ics($meeting, $user)
    {
        $start = $meeting['meeting_date'] . ' ' . $meeting['start_time'];
        $end   = $meeting['meeting_date'] . ' ' . $meeting['end_time'];

        $startDT = new DateTime($start, new DateTimeZone('Asia/Jakarta'));
        $endDT   = new DateTime($end, new DateTimeZone('Asia/Jakarta'));

        $startDT->setTimezone(new DateTimeZone('UTC'));
        $endDT->setTimezone(new DateTimeZone('UTC'));

        $dtStart = $startDT->format('Ymd\THis\Z');
        $dtEnd   = $endDT->format('Ymd\THis\Z');
        $dtStamp = gmdate('Ymd\THis\Z');

        if ($meeting['meeting_type'] === 'offline') {
            $location = $meeting['room_name'] ?? 'TBD';
        } elseif ($meeting['meeting_type'] === 'online') {
            $location = 'Online Meeting';
        } else {
            $location = ($meeting['room_name'] ?? 'TBD') . ' / Online';
        }

        $description = "MEETING DETAILS\\n\\n";
        $description .= "Topic: " . $this->ics_escape($meeting['topic']) . "\\n";
        $description .= "Organizer: " . $this->ics_escape($meeting['requester_name']) . "\\n";
        $description .= "Time: " . date('H:i', strtotime($meeting['start_time'])) . " - " . date('H:i', strtotime($meeting['end_time'])) . " WIB\\n";
        $description .= "Duration: " . $meeting['duration'] . " minutes\\n\\n";

        if (!empty($meeting['meeting_link'])) {
            $description .= "Join: " . $meeting['meeting_link'] . "\\n";
        }

        if (!empty($meeting['passcode'])) {
            $description .= "Passcode: " . $meeting['passcode'] . "\\n";
        }

        if (!empty($meeting['description'])) {
            $description .= "\\nAgenda:\\n" . $this->ics_escape($meeting['description']);
        }

        $organizer_email = !empty($meeting['requester_email']) 
            ? $meeting['requester_email'] 
            : $this->config['from_email'];

        $uid = 'meeting-' . $meeting['id'] . '@tjbps.com';

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//PT TJB Power Services//Meeting System v1.0//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:REQUEST\r\n";

        $ics .= "BEGIN:VTIMEZONE\r\n";
        $ics .= "TZID:Asia/Jakarta\r\n";
        $ics .= "BEGIN:STANDARD\r\n";
        $ics .= "DTSTART:19700101T000000\r\n";
        $ics .= "TZOFFSETFROM:+0700\r\n";
        $ics .= "TZOFFSETTO:+0700\r\n";
        $ics .= "END:STANDARD\r\n";
        $ics .= "END:VTIMEZONE\r\n";

        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . $uid . "\r\n";
        $ics .= "DTSTAMP:" . $dtStamp . "\r\n";
        $ics .= "DTSTART:" . $dtStart . "\r\n";
        $ics .= "DTEND:" . $dtEnd . "\r\n";
        $ics .= "SUMMARY:" . $this->ics_escape($meeting['topic']) . "\r\n";
        $ics .= "DESCRIPTION:" . $description . "\r\n";
        $ics .= "LOCATION:" . $this->ics_escape($location) . "\r\n";
        
        $ics .= "ORGANIZER;CN=\"" . $this->ics_escape($meeting['requester_name']) . "\":MAILTO:" . $organizer_email . "\r\n";
        
        $ics .= "ATTENDEE;CN=\"" . $this->ics_escape($user['full_name']) . "\";ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE:MAILTO:" . $user['email'] . "\r\n";
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "SEQUENCE:0\r\n";
        $ics .= "PRIORITY:5\r\n";

        $ics .= "BEGIN:VALARM\r\n";
        $ics .= "TRIGGER:-PT15M\r\n";
        $ics .= "ACTION:DISPLAY\r\n";
        $ics .= "DESCRIPTION:Meeting Reminder\r\n";
        $ics .= "END:VALARM\r\n";

        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }


    private function ics_escape($text) {
        if (empty($text)) return '';
        
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('"', '\\"', $text);
        $text = str_replace(',', '\\,', $text);
        $text = str_replace(';', '\\;', $text);
        $text = str_replace("\n", '\\n', $text);
        $text = str_replace("\r", '', $text);
        
        return $text;
    }


    private function get_meeting_details($meeting_id) {
        return $this->CI->db
            ->select('
                meetings.*,
                users.full_name AS requester_name,
                users.email AS requester_email,
                meeting_rooms.room_name,
                meeting_platforms.platform_name
            ')
            ->from('meetings')
            ->join('users', 'users.id = meetings.requested_by', 'left')
            ->join('meeting_rooms', 'meeting_rooms.id = meetings.room_id', 'left')
            ->join('meeting_platforms', 'meeting_platforms.id = meetings.platform_id', 'left')
            ->where('meetings.id', $meeting_id)
            ->get()
            ->row_array();
    }
}