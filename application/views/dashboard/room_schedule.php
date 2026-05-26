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

        /* Navigation & Room Schedule Styles */
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

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .room-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .room-card.selected {
            border-color: #1e3a5f;
            background: #f0f7ff;
        }

        .room-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .room-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .room-info h3 {
            font-size: 18px;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .room-info p {
            font-size: 13px;
            color: #666;
        }

        .room-activities {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .activity-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .activity-item {
            display: inline-block;
            padding: 5px 12px;
            background: white;
            border-radius: 20px;
            font-size: 12px;
            margin: 3px;
            border: 1px solid #e0e0e0;
        }

        .schedule-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .schedule-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .schedule-title {
            font-size: 22px;
            color: #1e3a5f;
            font-weight: bold;
        }

        .date-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-selector input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .timeline {
            position: relative;
            padding-left: 100px;
        }

        .meeting-slot {
            display: flex;
            padding: 20px;
            margin-bottom: 15px;
            background: #f9f9f9;
            border-left: 4px solid #1e3a5f;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .meeting-slot:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .slot-time {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a5f;
            min-width: 120px;
        }

        .slot-details {
            flex: 1;
        }

        .slot-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .slot-meta {
            font-size: 13px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-online { background: #e3f2fd; color: #1976d2; }
        .badge-offline { background: #fff3e0; color: #f57c00; }
        .badge-hybrid { background: #f3e5f5; color: #7b1fa2; }
        .badge-scheduled { background: #e8f5e9; color: #388e3c; }
        .badge-ongoing { background: #fff3e0; color: #f57c00; }
        .badge-completed { background: #e0e0e0; color: #757575; }
        .badge-cancelled { background: #ffebee; color: #d32f2f; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .btn {
            padding: 10px 25px;
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

        .btn:hover { background: #2c5282; }

        .room-status {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50;
            margin-right: 5px;
        }

        .room-status.busy { background: #f44336; }
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
                    <?= strtoupper(substr($this->session->userdata('full_name'), 0, 1)) ?>
                </div>
                <div class="user-info-header">
                    <div class="user-name"><?= $this->session->userdata('full_name') ?></div>
                    <div class="user-role-badge"><?= ucfirst($this->session->userdata('role')) ?></div>
                </div>
                <span class="dropdown-arrow">▼</span>
            </div>

            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        <?= strtoupper(substr($this->session->userdata('full_name'), 0, 1)) ?>
                    </div>
                    <div class="dropdown-name"><?= $this->session->userdata('full_name') ?></div>
                    <div class="dropdown-role"><?= ucfirst($this->session->userdata('role')) ?></div>
                </div>
                
                <div class="dropdown-body">
                    <?php if($this->session->userdata('employee_id') || $this->session->userdata('position')): ?>
                    <div class="info-section">
                        <div class="section-title">Personal Information</div>
                        <?php if($this->session->userdata('employee_id')): ?>
                        <div class="info-row">
                            <div class="info-label">Employee ID</div>
                            <div class="info-value"><?= $this->session->userdata('employee_id') ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if($this->session->userdata('position')): ?>
                        <div class="info-row">
                            <div class="info-label">Position</div>
                            <div class="info-value"><?= $this->session->userdata('position') ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-section">
                        <div class="section-title">Account Information</div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= $this->session->userdata('email') ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Username</div>
                            <div class="info-value"><?= $this->session->userdata('username') ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="dropdown-divider"></div>
                
                <div style="padding: 0 20px 20px;">
                    <a href="<?= base_url('auth/logout') ?>" class="logout-button">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="nav-menu">
    <a href="<?= base_url('dashboard') ?>">Today</a>
    <a href="<?= base_url('dashboard/calendar') ?>">Calendar</a>
    <a href="<?= base_url('meeting') ?>">All Meetings</a>
    <a href="<?= base_url('dashboard/room-schedule') ?>" class="active">Room Schedule</a>
    <?php if ($this->session->userdata('role') === 'admin'): ?>
        <a href="<?= base_url('admin/users') ?>">Manage Users</a>
    <?php endif; ?>
</div>

<div class="container">
    <h2 style="margin-bottom:25px;color:#1e3a5f;">Select a Room</h2>

    <div class="rooms-grid">
        <?php foreach ($rooms as $room): ?>
        <div class="room-card <?= $selectedRoom && $selectedRoom['id']==$room['id'] ? 'selected':'' ?>"
             onclick="location.href='<?= base_url('dashboard/room-schedule/'.$room['id']) ?>'">
            <div class="room-header">
                <div class="room-icon">🏢</div>
                <div class="room-info">
                    <h3><?= htmlspecialchars($room['room_name']) ?></h3>
                    <p>
                        <span class="room-status <?= !empty($roomMeetingMap[$room['id']])?'busy':'' ?>"></span>
                        Capacity: <?= $room['capacity'] ?> persons
                    </p>
                </div>
            </div>

            <div class="room-activities">
                <div class="activity-label">Today's Activities:</div>
                <?php if (!empty($roomMeetingMap[$room['id']])): ?>
                    <?php foreach (array_slice($roomMeetingMap[$room['id']],0,3) as $topic): ?>
                        <span class="activity-item"><?= htmlspecialchars($topic) ?></span>
                    <?php endforeach; ?>
                    <?php if (count($roomMeetingMap[$room['id']])>3): ?>
                        <span class="activity-item">+<?= count($roomMeetingMap[$room['id']])-3 ?> more</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="activity-item">No activity</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php if ($selectedRoom): ?>
<?php $displayDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>

<div class="schedule-section">
    <div class="schedule-header">
        <div class="schedule-title">
            <?= htmlspecialchars($selectedRoom['room_name']) ?> -
            <?= date('d M Y',strtotime($displayDate)) ?> Schedule
        </div>
        <div class="date-selector">
            <input type="date" id="scheduleDate" value="<?= $displayDate ?>" onchange="changeDate()">
            <?php if ($this->session->userdata('role')==='admin'): ?>
                <a href="<?= base_url('meeting/create?room_id='.$selectedRoom['id'].'&date='.$displayDate) ?>" class="btn">
                    + Book This Room
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="timeline">
        <?php if (empty($meetings)): ?>
            <div class="empty-state">
                <h3>No meetings scheduled</h3>
            </div>
        <?php else: ?>
            <?php foreach ($meetings as $meeting): ?>
            <div class="meeting-slot">
                <div class="slot-time">
                    <?= date('H:i',strtotime($meeting['start_time'])) ?> -
                    <?= date('H:i',strtotime($meeting['end_time'])) ?>
                </div>
                <div class="slot-details">
                    <div class="slot-title">
                        <?= htmlspecialchars($meeting['topic']) ?>
                        <span class="badge badge-<?= $meeting['meeting_type'] ?>">
                            <?= ucfirst($meeting['meeting_type']) ?>
                        </span>
                        <span class="badge badge-<?= $meeting['status'] ?>">
                            <?= ucfirst($meeting['status']) ?>
                        </span>
                    </div>
                    <div class="slot-meta">
                        Organized by <strong><?= htmlspecialchars($meeting['requester_name']) ?></strong>
                        | <?= $meeting['duration'] ?> minutes
                        <?php if ($meeting['meeting_link']): ?>
                            | <a href="<?= htmlspecialchars($meeting['meeting_link']) ?>" target="_blank">Join</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
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

    function changeDate(){
        const d=document.getElementById('scheduleDate').value;
        const r=<?= $selectedRoom ? $selectedRoom['id']:'null' ?>;
        if(r) location.href=`<?= base_url('dashboard/room-schedule/') ?>${r}?date=${d}`;
    }
    
    setInterval(()=>{ location.reload(); },60000);
</script>

</body>
</html>