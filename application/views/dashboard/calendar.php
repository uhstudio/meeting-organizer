<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
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
        
        /* Profile Dropdown - Professional Design */
        .profile-container { position: relative; }
        
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
        
        .profile-trigger:hover { background: rgba(255, 255, 255, 0.2); }
        
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
        
        .user-info-header { text-align: left; }
        .user-name { font-weight: 600; font-size: 14px; margin-bottom: 3px; }
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
        
        .profile-trigger.active .dropdown-arrow { transform: rotate(180deg); }
        
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
        
        .dropdown-body { padding: 20px; }
        .info-section { margin-bottom: 18px; }
        
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
        
        .info-row:last-child { border-bottom: none; }
        
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
        
        .logout-button:hover { background: #b91c1c; }

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
        
        .container { padding: 40px; max-width: 1400px; margin: 0 auto; }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .month-selector {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .month-selector button {
            background: #1e3a5f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .month-selector button:hover { background: #2c5282; }
        
        .month-display {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a5f;
        }
        
        .calendar-grid {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .calendar-days-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .day-header {
            text-align: center;
            font-weight: bold;
            color: #1e3a5f;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .calendar-day {
            min-height: 120px;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background: white;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .calendar-day:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .calendar-day.other-month {
            background: #f9f9f9;
            opacity: 0.5;
        }
        
        .calendar-day.today {
            background: #e3f2fd;
            border: 2px solid #1e3a5f;
        }
        
        .day-number {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .calendar-day.today .day-number { color: #1e3a5f; }
        
        .day-meetings { font-size: 12px; }
        
        .meeting-dot {
            display: inline-block;
            width: 100%;
            padding: 4px 8px;
            margin: 2px 0;
            background: #1e3a5f;
            color: white;
            border-radius: 3px;
            font-size: 11px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .meeting-dot.online { background: #2196f3; }
        .meeting-dot.offline { background: #ff9800; }
        .meeting-dot.hybrid { background: #9c27b0; }
        
        .legend {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
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
            background: #1e3a5f;
            color: white;
        }
        
        .btn:hover {
            background: #2c5282;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30,58,95,0.3);
        }
    </style>
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
        <a href="<?= base_url('dashboard') ?>">Today</a>
        <a href="<?= base_url('dashboard/calendar') ?>" class="active">Calendar</a>
        <a href="<?= base_url('meeting') ?>">All Meetings</a>
        <a href="<?= base_url('dashboard/room-schedule') ?>">Room Schedule</a>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <a href="<?= base_url('admin/users') ?>">Manage Users</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <div class="calendar-header">
            <div class="month-selector">
                <button onclick="previousMonth()">← Previous</button>
                <div class="month-display" id="monthDisplay">
                    <?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?>
                </div>
                <button onclick="nextMonth()">Next →</button>
            </div>
            <a href="<?= base_url('meeting/create') ?>" class="btn">+ Create Meeting</a>
        </div>

        <div class="calendar-grid">
            <div class="calendar-days-header">
                <div class="day-header">Sun</div>
                <div class="day-header">Mon</div>
                <div class="day-header">Tue</div>
                <div class="day-header">Wed</div>
                <div class="day-header">Thu</div>
                <div class="day-header">Fri</div>
                <div class="day-header">Sat</div>
            </div>

            <div class="calendar-days" id="calendarDays">
                <?php
                $firstDay = mktime(0, 0, 0, $month, 1, $year);
                $daysInMonth = date('t', $firstDay);
                $dayOfWeek = date('w', $firstDay);
                
                $prevMonth = $month - 1;
                $prevYear = $year;
                if ($prevMonth < 1) {
                    $prevMonth = 12;
                    $prevYear--;
                }
                $daysInPrevMonth = date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
                
                for ($i = $dayOfWeek - 1; $i >= 0; $i--) {
                    $day = $daysInPrevMonth - $i;
                    echo '<div class="calendar-day other-month">';
                    echo '<div class="day-number">' . $day . '</div>';
                    echo '</div>';
                }
                
                $today = date('Y-m-d');
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = ($currentDate === $today) ? 'today' : '';
                    
                    echo '<div class="calendar-day ' . $isToday . '" onclick="viewDay(\'' . $currentDate . '\')">';
                    echo '<div class="day-number">' . $day . '</div>';
                    echo '<div class="day-meetings">';
                    
                    $meetingCount = 0;
                    foreach ($meetings as $meeting) {
                        if ($meeting['meeting_date'] === $currentDate) {
                            $meetingCount++;
                            if ($meetingCount <= 3) { // Show max 3 meetings
                                $time = date('H:i', strtotime($meeting['start_time']));
                                $truncatedTopic = strlen($meeting['topic']) > 12 ? substr($meeting['topic'], 0, 12) . '...' : $meeting['topic'];
                                echo '<div class="meeting-dot ' . $meeting['meeting_type'] . '" title="' . htmlspecialchars($meeting['topic'], ENT_QUOTES, 'UTF-8') . '">';
                                echo $time . ' ' . $truncatedTopic;
                                echo '</div>';
                            }
                        }
                    }
                    
                    if ($meetingCount > 3) {
                        echo '<div class="meeting-dot" style="background: #666;">+' . ($meetingCount - 3) . ' more</div>';
                    }
                    
                    echo '</div>';
                    echo '</div>';
                }
                
                $remainingDays = 42 - ($dayOfWeek + $daysInMonth);
                for ($day = 1; $day <= $remainingDays; $day++) {
                    echo '<div class="calendar-day other-month">';
                    echo '<div class="day-number">' . $day . '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #2196f3;"></div>
                    <span>Online Meeting</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ff9800;"></div>
                    <span>Offline Meeting</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #9c27b0;"></div>
                    <span>Hybrid Meeting</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const currentMonth = <?= $month ?>;
        const currentYear = <?= $year ?>;

        function previousMonth() {
            let month = currentMonth - 1;
            let year = currentYear;
            if (month < 1) {
                month = 12;
                year--;
            }
            window.location.href = `<?= base_url('dashboard/calendar') ?>?month=${month}&year=${year}`;
        }

        function nextMonth() {
            let month = currentMonth + 1;
            let year = currentYear;
            if (month > 12) {
                month = 1;
                year++;
            }
            window.location.href = `<?= base_url('dashboard/calendar') ?>?month=${month}&year=${year}`;
        }

        function viewDay(date) {
            window.location.href = `<?= base_url('meeting') ?>?date=${date}`;
        }

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

    </script>
</body>
</html>