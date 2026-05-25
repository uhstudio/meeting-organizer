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

        /* Navigation & Meeting Management Styles */
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

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #1e3a5f;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .stat-card.active {
            background: #f0f7ff;
            border-left-width: 6px;
        }

        .stat-card.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            color: inherit;
            font-weight: bold;
            font-size: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex: 1;
            max-width: 800px;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-select {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            min-width: 150px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
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

        .btn-clear {
            background: #6c757d;
            color: white;
            padding: 12px 20px;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1e3a5f;
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background: #f9f9f9;
        }

        td {
            padding: 15px;
            font-size: 14px;
            color: #333;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-online { background: #e3f2fd; color: #1976d2; }
        .badge-offline { background: #fff3e0; color: #f57c00; }
        .badge-hybrid { background: #f3e5f5; color: #7b1fa2; }
        .badge-recurring { background: #e8f5e9; color: #388e3c; }
        .badge-scheduled { background: #e3f2fd; color: #1976d2; }
        .badge-completed { background: #e8f5e9; color: #388e3c; }
        .badge-cancelled { background: #ffebee; color: #c62828; }
        .badge-ongoing { background: #fff3e0; color: #f57c00; }

        select.badge {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 4px center;
            background-size: 12px;
            padding-right: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit { background: #2196f3; color: white; }
        .btn-edit:hover { background: #1976d2; }
        .btn-delete { background: #f44336; color: white; }
        .btn-delete:hover { background: #c62828; }
        .btn-view { background: #4caf50; color: white; }
        .btn-view:hover { background: #388e3c; }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
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
        <a href="<?= base_url('meeting') ?>" class="active">All Meetings</a>
        <a href="<?= base_url('dashboard/room-schedule') ?>">Room Schedule</a>
        <?php if($this->session->userdata('role') === 'admin'): ?>
        <a href="<?= base_url('admin/users') ?>">Manage Users</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
            <?= $this->session->flashdata('error') ?>
        </div>
        <?php endif; ?>

        <div class="stats-grid">
            <!-- Total Meetings -->
            <div class="stat-card" data-filter-type="total">
                <div class="stat-value"><?= $total_meetings ?></div>
                <div class="stat-label">Total Meetings</div>
            </div>
            
            <!-- Scheduled -->
            <div class="stat-card <?= ($filter_status === 'scheduled') ? 'active' : '' ?>" 
                 style="border-left-color: #4caf50; cursor: pointer;"
                 data-filter-status="scheduled">
                <div class="stat-value" style="color: #4caf50;">
                    <?= $scheduled ?>
                </div>
                <div class="stat-label">Scheduled</div>
            </div>
            
            <!-- Online Meetings -->
            <div class="stat-card <?= ($filter_type === 'online') ? 'active' : '' ?>" 
                 style="border-left-color: #2196f3; cursor: pointer;"
                 data-filter-type="online">
                <div class="stat-value" style="color: #2196f3;">
                    <?= $online ?>
                </div>
                <div class="stat-label">Online Meetings</div>
            </div>
            
            <!-- Offline Meetings -->
            <div class="stat-card <?= ($filter_type === 'offline') ? 'active' : '' ?>" 
                 style="border-left-color: #ff9800; cursor: pointer;"
                 data-filter-type="offline">
                <div class="stat-value" style="color: #ff9800;">
                    <?= $offline ?>
                </div>
                <div class="stat-label">Offline Meetings</div>
            </div>
            
            <!-- Hybrid Meetings -->
            <div class="stat-card <?= ($filter_type === 'hybrid') ? 'active' : '' ?>" 
                 style="border-left-color: #9c27b0; cursor: pointer;"
                 data-filter-type="hybrid">
                <div class="stat-value" style="color: #9c27b0;">
                    <?= $hybrid ?>
                </div>
                <div class="stat-label">Hybrid Meetings</div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-bar">
                <input type="text" class="search-input" id="searchInput" placeholder="Search meetings..." value="">
                
                <select class="filter-select" id="monthFilter">
                    <option value="">All Months</option>
                    <?php
                    for ($i = -12; $i <= 3; $i++) {
                        $month_date = date('Y-m', strtotime("$i months"));
                        $month_name = date('F Y', strtotime("$i months"));
                        $selected = ($filter_month === $month_date) ? 'selected' : '';
                        echo "<option value='$month_date' $selected>$month_name</option>";
                    }
                    ?>
                </select>

                <select class="filter-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="scheduled" <?= ($filter_status === 'scheduled') ? 'selected' : '' ?>>Scheduled</option>
                    <option value="ongoing" <?= ($filter_status === 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
                    <option value="completed" <?= ($filter_status === 'completed') ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($filter_status === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>

                <?php if ($filter_month || $filter_type || $filter_status): ?>
                <a href="<?= base_url('meeting') ?>" class="btn btn-clear">Clear Filters</a>
                <?php endif; ?>
            </div>
            <?php if($this->session->userdata('role') === 'admin'): ?>
            <a href="<?= base_url('meeting/create') ?>" class="btn btn-primary">+ Create Meeting</a>
            <?php endif; ?>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Meeting Code</th>
                        <th>Topic</th>
                        <th>Type</th>
                        <th>Date & Time</th>
                        <th>Duration</th>
                        <th>Requested By</th>
                        <th>Room/Platform</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="meetingTable">
                    <?php if(empty($meetings)): ?>
                    <tr>
                        <td colspan="9" class="no-data">
                            <h3>No meetings found</h3>
                            <p>Create your first meeting to get started</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($meetings as $meeting): ?>
                        <tr data-type="<?= $meeting['meeting_type'] ?>" data-status="<?= $meeting['status'] ?>">
                            <td><strong><?= htmlspecialchars($meeting['meeting_code'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                            <td>
                                <?= htmlspecialchars($meeting['topic'], ENT_QUOTES, 'UTF-8') ?>
                                <?php if($meeting['is_recurring']): ?>
                                <span class="badge badge-recurring">Recurring</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $meeting['meeting_type'] ?>">
                                    <?= ucfirst($meeting['meeting_type']) ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d M Y', strtotime($meeting['meeting_date'])) ?><br>
                                <small><?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?></small>
                            </td>
                            <td><?= $meeting['duration'] ?> min</td>
                            <td><?= htmlspecialchars($meeting['requester_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if($meeting['room_name']): ?>
                                    <strong><?= htmlspecialchars($meeting['room_name'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                <?php endif; ?>
                                <?php if($meeting['platform_name']): ?>
                                    <small><?= htmlspecialchars($meeting['platform_name'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($this->session->userdata('role') === 'admin'): ?>
                                <select onchange="changeStatus(<?= $meeting['id'] ?>, this.value)" 
                                        class="badge badge-<?= $meeting['status'] ?>" 
                                        style="cursor: pointer; border: none; font-weight: 600;">
                                    <option selected disabled>
                                        <?= ucfirst($meeting['status']) ?>
                                    </option>
                                    <?php if($meeting['status'] !== 'scheduled'): ?>
                                    <option value="scheduled">Scheduled</option>
                                    <?php endif; ?>
                                    <?php if($meeting['status'] !== 'ongoing'): ?>
                                    <option value="ongoing">Ongoing</option>
                                    <?php endif; ?>
                                    <?php if($meeting['status'] !== 'completed'): ?>
                                    <option value="completed">Completed</option>
                                    <?php endif; ?>
                                    <?php if($meeting['status'] !== 'cancelled'): ?>
                                    <option value="cancelled">Cancelled</option>
                                    <?php endif; ?>
                                </select>
                                <?php else: ?>
                                <span class="badge badge-<?= $meeting['status'] ?>">
                                    <?= ucfirst($meeting['status']) ?>
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php
                                    $canEdit = $this->session->userdata('role') === 'admin' || 
                                            $meeting['requested_by'] == $this->session->userdata('user_id');
                                    ?>
                                    
                                    <?php if($this->session->userdata('role') === 'admin'): ?>
                                        <a href="<?= base_url('attendance/view/' . $meeting['id']) ?>" 
                                    class="btn-sm" 
                                    style="background: #4caf50; color: white;">
                                        Attendance
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if($canEdit): ?>
                                    <a href="<?= base_url('meeting/edit/' . $meeting['id']) ?>" 
                                    class="btn-sm btn-edit">
                                        Edit
                                    </a>
                                    
                                    <a href="<?= base_url('meeting/delete/' . $meeting['id']) ?>" 
                                    class="btn-sm btn-delete" 
                                    onclick="return confirm('Are you sure you want to delete this meeting?')">
                                        Delete
                                    </a>
                                    <?php else: ?>
                                    <span class="btn-sm btn-view">View Only</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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

        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                const filterType = this.getAttribute('data-filter-type');
                const filterStatus = this.getAttribute('data-filter-status');
                
                let url = '<?= base_url('meeting') ?>?';
                const params = new URLSearchParams(window.location.search);
                
                if (filterType) {
                    if (filterType === 'total') {
                        window.location.href = '<?= base_url('meeting') ?>';
                        return;
                    } else {
                        params.set('type', filterType);
                        params.delete('status'); 
                    }
                } else if (filterStatus) {
                    params.set('status', filterStatus);
                    params.delete('type'); 
                }
                
                const monthFilter = document.getElementById('monthFilter').value;
                if (monthFilter) {
                    params.set('month', monthFilter);
                }
                
                window.location.href = '<?= base_url('meeting') ?>?' + params.toString();
            });
        });

        // ========== MONTH FILTER ==========
        const monthFilter = document.getElementById('monthFilter');
        
        monthFilter.addEventListener('change', function() {
            const selectedMonth = this.value;
            const params = new URLSearchParams(window.location.search);
            
            if (selectedMonth) {
                params.set('month', selectedMonth);
            } else {
                params.delete('month');
            }
            
            window.location.href = '<?= base_url('meeting') ?>?' + params.toString();
        });

        const statusFilter = document.getElementById('statusFilter');
        
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const params = new URLSearchParams(window.location.search);
            
            if (selectedStatus) {
                params.set('status', selectedStatus);
                params.delete('type'); 
            } else {
                params.delete('status');
            }
            
            const monthFilter = document.getElementById('monthFilter').value;
            if (monthFilter) {
                params.set('month', monthFilter);
            }
            
            window.location.href = '<?= base_url('meeting') ?>?' + params.toString();
        });

        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('#meetingTable tr');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            tableRows.forEach(row => {
                if (row.querySelector('.no-data')) return;

                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        function changeStatus(meetingId, newStatus) {
            if (!newStatus) return;
            
            if (confirm('Are you sure you want to change the status to ' + newStatus + '?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('meeting/change_status/') ?>' + meetingId;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= $this->security->get_csrf_token_name() ?>';
                csrfInput.value = '<?= $this->security->get_csrf_hash() ?>';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = newStatus;
                
                form.appendChild(csrfInput);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                event.target.selectedIndex = 0;
            }
        }

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