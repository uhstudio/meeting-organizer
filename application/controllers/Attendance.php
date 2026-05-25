<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Attendance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Meeting_attendance_model');
        $this->load->model('Meeting_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function mark($meeting_id = null) {
        
        if (!$meeting_id) {
            $this->show_error_page('Invalid meeting link');
            return;
        }

        $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);
        
        if (!$meeting) {
            $this->show_error_page('Meeting not found');
            return;
        }

        $now = new DateTime();
        $meeting_start = new DateTime($meeting['meeting_date'] . ' ' . $meeting['start_time']);
        $meeting_end_of_day = new DateTime($meeting['meeting_date'] . ' 23:59:59');

        if ($now < $meeting_start) {
            $this->show_error_page('Attendance form will be available at ' . $meeting_start->format('H:i') . ' WIB on ' . date('l, F j, Y', strtotime($meeting['meeting_date'])));
            return;
        }

        if ($now > $meeting_end_of_day) {
            $this->show_error_page('Attendance period has ended. The form was available until 23:59 WIB on ' . date('l, F j, Y', strtotime($meeting['meeting_date'])));
            return;
        }

        $data = [
            'title' => 'Meeting Attendance',
            'meeting' => $meeting
        ];

        $this->load->view('attendance/form', $data);
    }

    public function submit() {
        
        $meeting_id = $this->input->post('meeting_id');
        
        if (!$meeting_id) {
            $this->show_error_page('Invalid request');
            return;
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('company', 'Company', 'required|trim');
        $this->form_validation->set_rules('position', 'Position', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);

            $data = [
                'title' => 'Meeting Attendance',
                'meeting' => $meeting,
                'errors' => validation_errors()
            ];

            $this->load->view('attendance/form', $data);
            return;
        }

        $email = $this->input->post('email');
        $duplicate = $this->Meeting_attendance_model->check_duplicate($meeting_id, $email);

        if ($duplicate) {
            $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);
            
            $data = [
                'title' => 'Already Registered',
                'success' => true,
                'message' => 'Your attendance has already been recorded',
                'meeting' => $duplicate,
                'already_marked' => true
            ];
            
            $this->load->view('attendance/success', $data);
            return;
        }

        $attendance_data = [
            'meeting_id' => $meeting_id,
            'name' => $this->input->post('name'),
            'company' => $this->input->post('company'),
            'position' => $this->input->post('position'),
            'email' => $email,
            'phone' => $this->input->post('phone')
        ];

        $result = $this->Meeting_attendance_model->create_attendance($attendance_data);

        if ($result) {
            $attendance_id = $this->db->insert_id();
            $attendance = $this->Meeting_attendance_model->get_by_id($attendance_id);
            
            $data = [
                'title' => 'Attendance Confirmed',
                'success' => true,
                'message' => 'Thank you! Your attendance has been successfully recorded.',
                'meeting' => $attendance,
                'already_marked' => false
            ];
            
            $this->load->view('attendance/success', $data);
        } else {
            $this->show_error_page('Failed to save attendance. Please try again.');
        }
    }

    public function view($meeting_id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can view attendance');
            redirect('dashboard');
        }

        $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);
        
        if (!$meeting) {
            show_404();
        }

        $attendance_list = $this->Meeting_attendance_model->get_by_meeting($meeting_id);
        $statistics = $this->Meeting_attendance_model->get_meeting_statistics($meeting_id);

        $data = [
            'title' => 'Meeting Attendance - ' . $meeting['topic'],
            'meeting' => $meeting,
            'attendance_list' => $attendance_list,
            'statistics' => $statistics
        ];

        $this->load->view('attendance/view', $data);
    }

    private function show_error_page($message) {
        $data = [
            'title' => 'Attendance Error',
            'message' => $message
        ];
        $this->load->view('attendance/error', $data);
    }

    public function export_excel($meeting_id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can export attendance');
            redirect('dashboard');
        }

        require_once FCPATH . 'vendor/autoload.php';
        
        $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);
        $attendance_list = $this->Meeting_attendance_model->get_by_meeting($meeting_id);
        
        if (!$meeting) {
            show_404();
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $spreadsheet->getProperties()
            ->setCreator('PT TJB Power Services')
            ->setTitle('Meeting Attendance - ' . $meeting['topic'])
            ->setSubject('Attendance Report')
            ->setDescription('Meeting attendance export for ' . $meeting['topic']);
        
        // ========== HEADER SECTION (Meeting Info) ==========
        // Row 1
        $sheet->setCellValue('A1', 'PT TJB POWER SERVICES');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Row 2
        $sheet->setCellValue('A2', 'MEETING ATTENDANCE REPORT');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
        // Row 4-7
        $sheet->setCellValue('A4', 'Meeting Topic:');
        $sheet->setCellValue('B4', $meeting['topic']);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        
        $sheet->setCellValue('A5', 'Date:');
        $sheet->setCellValue('B5', date('l, F j, Y', strtotime($meeting['meeting_date'])));
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Time:');
        $sheet->setCellValue('B6', date('H:i', strtotime($meeting['start_time'])) . ' - ' . date('H:i', strtotime($meeting['end_time'])) . ' WIB');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        
        $sheet->setCellValue('A7', 'Organizer:');
        $sheet->setCellValue('B7', $meeting['requester_name']);
        $sheet->getStyle('A7')->getFont()->setBold(true);
        
        
        // ========== TABLE HEADER ==========
        // Row 9
        $headers = ['No', 'Name', 'Company', 'Position', 'Email', 'Phone', 'Marked At', 'Status'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '9', $header);
            $sheet->getStyle($column . '9')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);
            $column++;
        }
        
        // ========== TABLE DATA ==========
        $row = 10;
        $no = 1;
        foreach ($attendance_list as $att) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $att['name']);
            $sheet->setCellValue('C' . $row, $att['company']);
            $sheet->setCellValue('D' . $row, $att['position']);
            $sheet->setCellValue('E' . $row, $att['email']);
            $sheet->setCellValue('F' . $row, $att['phone']);
            $sheet->setCellValue('G' . $row, date('d M Y, H:i', strtotime($att['marked_at'])));
            $sheet->setCellValue('H' . $row, ucfirst($att['status']));
            
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ]);
            
            $row++;
        }
        
        // ========== AUTO SIZE COLUMNS ==========
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // ========== GENERATE FILE ==========
        $filename = 'attendance_' . preg_replace('/[^a-z0-9]/i', '_', $meeting['topic']) . '_' . date('Y-m-d', strtotime($meeting['meeting_date'])) . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


    public function export_pdf($meeting_id) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Only administrators can export attendance');
            redirect('dashboard');
        }

        require_once FCPATH . 'vendor/autoload.php';
        
        $meeting = $this->Meeting_model->get_meeting_detail($meeting_id);
        $attendance_list = $this->Meeting_attendance_model->get_by_meeting($meeting_id);
        $statistics = $this->Meeting_attendance_model->get_meeting_statistics($meeting_id);
        
        if (!$meeting) {
            show_404();
        }

        $data = [
            'meeting' => $meeting,
            'attendance_list' => $attendance_list,
            'statistics' => $statistics
        ];

        $html = $this->load->view('attendance/pdf_template', $data, TRUE);
        
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); 
        
        $dompdf = new \Dompdf\Dompdf($options);
        
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4', 'portrait');
        
        $dompdf->render();
        
        $filename = 'attendance_' . preg_replace('/[^a-z0-9]/i', '_', $meeting['topic']) . '_' . date('Y-m-d', strtotime($meeting['meeting_date'])) . '.pdf';
        
        $dompdf->stream($filename, array('Attachment' => 0)); 
    }
}