<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        
        .header { 
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); 
            color: white; 
            padding: 20px 40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        
        .header-left { display: flex; flex-direction: column; }
        .date-time { font-size: 36px; font-weight: bold; margin-bottom: 10px; }
        .clock { font-size: 28px; font-weight: 300; letter-spacing: 2px; }
        
        .header-center { text-align: center; flex: 1; margin: 0 40px; }
        .company-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .page-title { font-size: 16px; opacity: 0.9; }
        
        .header-right { text-align: right; position: relative; }
        
        .profile-container {
            position: relative;
        }
        
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 16px 8px 8px;
            border-radius: 6px;
            transition: background 0.2s;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .profile-trigger:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 4px;
            background: #ffffff;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .user-info-header {
            text-align: left;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 3px;
        }
        
        .user-role-badge {
            font-size: 11px;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 3px;
            display: inline-block;
        }
        
        .dropdown-arrow {
            transition: transform 0.2s;
            font-size: 10px;
            margin-left: 4px;
        }
        
        .profile-trigger.active .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background: white;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            min-width: 360px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            z-index: 1000;
        }
        
        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-header {
            background: #1e3a5f;
            color: white;
            padding: 24px 20px;
            border-bottom: 3px solid #2c5282;
        }
        
        .dropdown-avatar {
            width: 64px;
            height: 64px;
            border-radius: 4px;
            background: white;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 28px;
            margin: 0 auto 14px;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .dropdown-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
            text-align: center;
        }
        
        .dropdown-role {
            font-size: 12px;
            text-align: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 12px;
            border-radius: 3px;
            display: inline-block;
            margin: 0 auto;
            width: fit-content;
        }
        
        .dropdown-body {
            padding: 20px;
        }
        
        .info-section {
            margin-bottom: 18px;
        }
        
        .section-title {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            font-weight: 600;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 6px;
        }
        
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            min-width: 120px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            font-size: 13px;
            color: #1a1a1a;
            font-weight: 500;
            flex: 1;
            word-break: break-word;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #e5e5e5;
            margin: 16px 0;
        }
        
        .logout-button {
            display: block;
            width: 100%;
            padding: 12px;
            background: #dc2626;
            color: white;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .logout-button:hover {
            background: #b91c1c;
        }
        
        .nav-menu { 
            background: white; 
            padding: 15px 40px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            display: flex; 
            gap: 20px; 
        }
        
        .nav-menu a { 
            text-decoration: none; 
            color: #333; 
            padding: 10px 20px; 
            border-radius: 5px; 
            transition: all 0.3s; 
        }
        
        .nav-menu a:hover, .nav-menu a.active { 
            background: #1e3a5f; 
            color: white; 
        }
        
        .container { 
            padding: 40px; 
            max-width: 1400px; 
            margin: 0 auto; 
        }
        
        .section-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
        }
        
        .section-title-main { 
            font-size: 20px; 
            color: #333; 
            font-weight: 600; 
        }
        
        .btn { 
            padding: 12px 30px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 14px; 
            transition: all 0.3s; 
            text-decoration: none; 
            display: inline-block; 
        }
        
        .btn-primary { 
            background: #1e3a5f; 
            color: white; 
        }
        
        .btn-primary:hover { 
            background: #2c5282; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(30,58,95,0.3); 
        }
        
        .meeting-list { 
            background: white; 
            border-radius: 10px; 
            padding: 30px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }
        
        .meeting-item { 
            display: flex; 
            padding: 20px; 
            border-left: 4px solid #1e3a5f; 
            margin-bottom: 20px; 
            background: #f9f9f9; 
            border-radius: 5px; 
            transition: all 0.3s; 
        }
        
        .meeting-item:hover { 
            transform: translateX(5px); 
            box-shadow: 0 3px 10px rgba(0,0,0,0.1); 
        }
        
        .meeting-time { 
            font-size: 18px; 
            font-weight: bold; 
            color: #1e3a5f; 
            min-width: 150px; 
        }
        
        .meeting-details { flex: 1; }
        
        .meeting-title { 
            font-size: 18px; 
            font-weight: 600; 
            color: #333; 
            margin-bottom: 5px; 
        }
        
        .meeting-meta { 
            font-size: 14px; 
            color: #666; 
        }
        
        .meeting-badge { 
            display: inline-block; 
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            margin-left: 10px; 
        }
        
        .badge-online { background: #e3f2fd; color: #1976d2; }
        .badge-offline { background: #fff3e0; color: #f57c00; }
        .badge-hybrid { background: #f3e5f5; color: #7b1fa2; }
        .badge-recurring { background: #e8f5e9; color: #388e3c; }
        
        .no-meetings { 
            text-align: center; 
            padding: 60px 20px; 
            color: #999; 
        }
        
        .no-meetings svg { 
            width: 80px; 
            height: 80px; 
            margin-bottom: 20px; 
            opacity: 0.3; 
        }
        
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9998;
            animation: fadeIn 0.3s;
        }
        
        .modal-overlay.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideUp 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .modal-header {
            background: #1e3a5f;
            color: white;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2c5282;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }
        
        .modal-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .detail-section {
            margin-bottom: 24px;
        }
        
        .detail-section-title {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            font-weight: 600;
            border-bottom: 2px solid #e5e5e5;
            padding-bottom: 6px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-item.full-width {
            grid-column: 1 / -1;
        }
        
        .detail-label {
            font-size: 11px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }
        
        .detail-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 500;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #1e3a5f;
        }
        
        .detail-value.empty {
            color: #999;
            font-style: italic;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-scheduled { background: #e3f2fd; color: #1976d2; }
        .status-ongoing { background: #fff3e0; color: #f57c00; }
        .status-completed { background: #e8f5e9; color: #388e3c; }
        .status-cancelled { background: #ffebee; color: #c62828; }
        
        .participants-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .participant-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 8px;
            border-left: 3px solid #1e3a5f;
        }
        
        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            background: #1e3a5f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .participant-info {
            flex: 1;
        }
        
        .participant-name {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 2px;
        }
        
        .participant-details {
            font-size: 12px;
            color: #666;
        }
        
        .loading-spinner {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .meeting-item {
            cursor: pointer;
        }
        
        .meeting-item:hover {
            background: #f0f0f0;
        }

        @media (max-width: 768px) {
            .header {
                padding: 10px 16px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 0;
            }
            .header-left {
                flex-direction: column;
                align-items: flex-start;
            }
            .date-time { font-size: 13px; margin-bottom: 2px; }
            .clock { font-size: 12px; letter-spacing: 1px; }
            .header-center {
                text-align: center;
                margin: 0 8px;
                flex: 1;
            }
            .company-name { font-size: 12px; }
            .page-title { font-size: 11px; }

            /* Sembunyikan nama & jabatan, tampilkan avatar saja */
            .user-info-header { display: none; }
            .dropdown-arrow { display: none; }
            .profile-trigger {
                padding: 4px;
                background: rgba(255,255,255,0.1);
            }
            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            /* Dropdown tetap muncul di bawah avatar */
            .profile-dropdown {
                min-width: 260px;
                right: 0;
            }

            /* Nav menu scroll horizontal */
            .nav-menu {
                padding: 8px 12px;
                gap: 6px;
                overflow-x: auto;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
            }
            .nav-menu::-webkit-scrollbar { display: none; }
            .nav-menu a {
                padding: 7px 12px;
                font-size: 13px;
                white-space: nowrap;
            }

            .container { padding: 16px; }
            .meeting-item { flex-direction: column; gap: 6px; }
            .meeting-time { min-width: unset; font-size: 14px; }
            .meeting-title { font-size: 15px; }
            .detail-grid { grid-template-columns: 1fr; }
            .modal-content { width: 96%; max-height: 92vh; }
        }

    </style>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.css'); ?>">
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="date-time" id="currentDate"></div>
            <div class="clock" id="currentTime"></div>
        </div>
        <div class="header-center">
            <div class="company-name">PT TJB POWER SERVICES</div>
            <div class="page-title">Meeting Organizer</div>
        </div>
        <div class="header-right">
            <div class="profile-container">
                <div class="profile-trigger" id="profileTrigger">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($this->session->userdata('full_name'), 0, 1)); ?>
                    </div>
                    <div class="user-info-header">
                        <div class="user-name"><?php echo $this->session->userdata('full_name'); ?></div>
                        <div class="user-role-badge"><?php echo ucfirst($this->session->userdata('position')); ?></div>
                    </div>
                    <span class="dropdown-arrow">▼</span>
                </div>

                <div class="profile-dropdown" id="profileDropdown">
                    <div class="dropdown-header">
                        <div class="dropdown-avatar">
                            <?php echo strtoupper(substr($this->session->userdata('full_name'), 0, 1)); ?>
                        </div>
                        <div class="dropdown-name"><?php echo $this->session->userdata('full_name'); ?></div>
                        <div class="dropdown-role"><?php echo ucfirst($this->session->userdata('role')); ?></div>
                    </div>
                    
                    <div class="dropdown-body">
                        <?php if($this->session->userdata('employee_id') || $this->session->userdata('position')): ?>
                        <div class="info-section">
                            <div class="section-title">Personal Information</div>
                            
                            <?php if($this->session->userdata('employee_id')): ?>
                            <div class="info-row">
                                <div class="info-label">Employee ID</div>
                                <div class="info-value"><?php echo $this->session->userdata('employee_id'); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($this->session->userdata('position')): ?>
                            <div class="info-row">
                                <div class="info-label">Position</div>
                                <div class="info-value"><?php echo $this->session->userdata('position'); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="info-section">
                            <div class="section-title">Account Information</div>
                            
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?php echo $this->session->userdata('email'); ?></div>
                            </div>
                            
                            <div class="info-row">
                                <div class="info-label">Username</div>
                                <div class="info-value"><?php echo $this->session->userdata('username'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dropdown-divider"></div>
                    
                    <div style="padding: 0 20px 20px;">
                        <a href="<?php echo base_url('auth/logout'); ?>" class="logout-button">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nav-menu">
        <a href="<?php echo base_url('dashboard'); ?>" class="active">Today</a>
        <a href="<?php echo base_url('dashboard/calendar'); ?>">Calendar</a>
        <a href="<?php echo base_url('meeting'); ?>">All Meetings</a>
        <a href="<?php echo base_url('dashboard/room-schedule'); ?>">Room Schedule</a>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <a href="<?php echo base_url('admin/users'); ?>">Manage Users</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="section-header">
            <div class="section-title-main">Today</div>
            <?php if($this->session->userdata('role') === 'admin'): ?>
            <a href="<?php echo base_url('meeting/create'); ?>" class="btn btn-primary">+ Create Meeting</a>
            <?php endif; ?>
        </div>

        <div class="meeting-list">
            <?php if(empty($meetings)): ?>
            <div class="no-meetings">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                </svg>
                <h3>No meetings scheduled for today</h3>
                <p>Create a new meeting to get started</p>
            </div>
            <?php else: ?>
                <?php foreach($meetings as $meeting): ?>
                <div class="meeting-item" data-meeting-id="<?php echo $meeting['id']; ?>" onclick="showMeetingDetail(<?php echo $meeting['id']; ?>)">
                    <div class="meeting-time">
                        <?php echo date('H:i', strtotime($meeting['start_time'])); ?> - <?php echo date('H:i', strtotime($meeting['end_time'])); ?>
                    </div>
                    <div class="meeting-details">
                        <div class="meeting-title">
                            <?php echo htmlspecialchars($meeting['topic']); ?>
                            <span class="meeting-badge badge-<?php echo $meeting['meeting_type']; ?>">
                                <?php echo ucfirst($meeting['meeting_type']); ?>
                            </span>
                            <?php if($meeting['is_recurring']): ?>
                                <span class="meeting-badge badge-recurring">Recurring</span>
                            <?php endif; ?>
                        </div>
                        <div class="meeting-meta">
                            Request by: <strong><?php echo htmlspecialchars($meeting['requester_name']); ?></strong>
                            <?php if($meeting['room_name']): ?>
                            | Room: <strong><?php echo htmlspecialchars($meeting['room_name']); ?></strong>
                            <?php endif; ?>
                            <?php if($meeting['platform_name']): ?>
                            | Platform: <strong><?php echo htmlspecialchars($meeting['platform_name']); ?></strong>
                            <?php endif; ?>
                            <?php if($this->session->userdata('role') === 'admin'): ?>
                            <div style="margin-top: 10px;">

                                <a href="<?= base_url('attendance/view/' . $meeting['id']) ?>" 
                                style="color: #4caf50; text-decoration: none; margin-right: 15px;">
                                    📋 View Attendance
                                </a>
                                
                                <a href="<?= base_url('meeting/edit/' . $meeting['id']) ?>" 
                                style="color: #2196f3; text-decoration: none; margin-right: 15px;">
                                    ✏️ Edit
                                </a>
                                
                                <a href="<?= base_url('meeting/delete/' . $meeting['id']) ?>" 
                                onclick="return confirm('Delete this meeting?')" 
                                style="color: #f44336; text-decoration: none;">
                                    🗑️ Delete
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal-overlay" id="meetingModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Meeting Details</div>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            const dateStr = now.toLocaleDateString('en-GB', options);
            document.getElementById('currentDate').textContent = dateStr;
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('currentTime').textContent = `${hours} : ${minutes} : ${seconds}`;
        }
        updateDateTime();
        setInterval(updateDateTime, 1000);

        const profileTrigger = document.getElementById('profileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');

        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            profileTrigger.classList.toggle('active');
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.profile-container')) {
                profileTrigger.classList.remove('active');
                profileDropdown.classList.remove('show');
            }
        });

        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        const modal = document.getElementById('meetingModal');
        const closeModalBtn = document.getElementById('closeModal');
        const modalBody = document.getElementById('modalBody');

        function showMeetingDetail(meetingId) {
            modal.classList.add('show');
            modalBody.innerHTML = '<div class="loading-spinner">Loading meeting details...</div>';

            fetch('<?php echo base_url("meeting/detail/"); ?>' + meetingId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalBody.innerHTML = '<div class="loading-spinner" style="color: #c62828;">' + data.error + '</div>';
                        return;
                    }
                    renderMeetingDetail(data);
                })
                .catch(error => {
                    modalBody.innerHTML = '<div class="loading-spinner" style="color: #c62828;">Failed to load meeting details</div>';
                    console.error('Error:', error);
                });
        }

        function renderMeetingDetail(meeting) {
            const statusClass = 'status-' + meeting.status;
            const statusText = meeting.status.charAt(0).toUpperCase() + meeting.status.slice(1);
            
            let participantsHtml = '';
            if (meeting.participants && meeting.participants.length > 0) {
                meeting.participants.forEach(participant => {
                    const initial = participant.full_name.charAt(0).toUpperCase();
                    const position = participant.position ? participant.position : 'No position';
                    const employeeId = participant.employee_id ? ' • ' + participant.employee_id : '';
                    
                    participantsHtml += `
                        <li class="participant-item">
                            <div class="participant-avatar">${initial}</div>
                            <div class="participant-info">
                                <div class="participant-name">${participant.full_name}</div>
                                <div class="participant-details">${position}${employeeId}</div>
                            </div>
                        </li>
                    `;
                });
            } else {
                participantsHtml = '<div class="detail-value empty">No participants</div>';
            }

            const roomInfo = meeting.room_name ? 
                `${meeting.room_name}${meeting.capacity ? ' (Capacity: ' + meeting.capacity + ')' : ''}` : 
                '<span class="empty">No room</span>';
            
            const platformInfo = meeting.platform_name || '<span class="empty">No platform</span>';
            const meetingLink = meeting.meeting_link ? 
                `<a href="${meeting.meeting_link}" target="_blank" style="color: #1976d2; text-decoration: underline;">${meeting.meeting_link}</a>` : 
                '<span class="empty">No link</span>';
            const passcode = meeting.passcode || '<span class="empty">No passcode</span>';
            const description = meeting.description || '<span class="empty">No description</span>';

            modalBody.innerHTML = `
                <div class="detail-section">
                    <div class="detail-section-title">Meeting Information</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Meeting Code</div>
                            <div class="detail-value">${meeting.meeting_code}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Status</div>
                            <div class="detail-value">
                                <span class="status-badge ${statusClass}">${statusText}</span>
                            </div>
                        </div>
                        <div class="detail-item full-width">
                            <div class="detail-label">Topic</div>
                            <div class="detail-value">${meeting.topic}</div>
                        </div>
                        <div class="detail-item full-width">
                            <div class="detail-label">Description</div>
                            <div class="detail-value">${description}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">Schedule</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Date</div>
                            <div class="detail-value">${meeting.formatted_date}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Time</div>
                            <div class="detail-value">${meeting.formatted_start_time} - ${meeting.formatted_end_time}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Duration</div>
                            <div class="detail-value">${meeting.duration} minutes</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Meeting Type</div>
                            <div class="detail-value">${meeting.meeting_type.charAt(0).toUpperCase() + meeting.meeting_type.slice(1)}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">Location & Platform</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Room</div>
                            <div class="detail-value">${roomInfo}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Platform</div>
                            <div class="detail-value">${platformInfo}</div>
                        </div>
                        <div class="detail-item full-width">
                            <div class="detail-label">Meeting Link</div>
                            <div class="detail-value">${meetingLink}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Passcode</div>
                            <div class="detail-value">${passcode}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">Organizer</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Requested By</div>
                            <div class="detail-value">${meeting.requester_name}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Position</div>
                            <div class="detail-value">${meeting.requester_position || '<span class="empty">No position</span>'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">${meeting.requester_email}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">Participants (${meeting.participants ? meeting.participants.length : 0})</div>
                    <ul class="participants-list">
                        ${participantsHtml}
                    </ul>
                </div>

                <div class="detail-section">
                    <div class="detail-section-title">Timestamps</div>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Created At</div>
                            <div class="detail-value">${meeting.formatted_created_at}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Last Updated</div>
                            <div class="detail-value">${meeting.formatted_updated_at}</div>
                        </div>
                    </div>
                </div>
            `;
        }

        closeModalBtn.addEventListener('click', function() {
            modal.classList.remove('show');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                modal.classList.remove('show');
            }
        });

        setInterval(() => {
            fetch('<?= base_url("dashboard/auto_update_api") ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); 
                    }
                })
                .catch(err => console.error('Auto update failed', err));
        }, 60000); 

    </script>
</body>
</html>