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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #1e3a5f;
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
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-filter {
            display: flex;
            gap: 10px;
            flex: 1;
            max-width: 600px;
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
        
        .badge-admin {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .badge-user {
            background: #f3e5f5;
            color: #7b1fa2;
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
        
        .btn-edit {
            background: #2196f3;
            color: white;
        }
        
        .btn-edit:hover {
            background: #1976d2;
        }
        
        .btn-delete {
            background: #f44336;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c62828;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
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
        <a href="<?= base_url('dashboard/room-schedule') ?>">Room Schedule</a>
        <a href="<?= base_url('admin/users') ?>" class="active">Manage Users</a>
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

        <?php if($this->session->flashdata('errors')): ?>
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                <?php foreach($this->session->flashdata('errors') as $error): ?>
                <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $total_users ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card" style="border-left-color: #2196f3;">
                <div class="stat-value" style="color: #2196f3;"><?= $total_admins ?></div>
                <div class="stat-label">Administrators</div>
            </div>
            <div class="stat-card" style="border-left-color: #9c27b0;">
                <div class="stat-value" style="color: #9c27b0;"><?= $total_regular_users ?></div>
                <div class="stat-label">Regular Users</div>
            </div>
        </div>

        <div class="toolbar">
            <div class="search-filter">
                <input type="text" class="search-input" id="searchInput" placeholder="Search by name, email, or username...">
                <select class="filter-select" id="roleFilter">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">+ Add New User</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php if(empty($users)): ?>
                    <tr>
                        <td colspan="7" class="no-data">
                            <h3>No users found</h3>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($users as $user): ?>
                        <tr data-role="<?= $user['role'] ?>">
                            <td><strong><?= htmlspecialchars($user['employee_id'] ?: '-', ENT_QUOTES, 'UTF-8') ?></strong></td>
                            <td><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($user['position'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <span class="badge badge-<?= $user['role'] ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($user['id'] != $this->session->userdata('user_id')): ?>
                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn-sm btn-edit">Edit</a>
                                    <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                       class="btn-sm btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                    <?php else: ?>
                                    <span style="color: #999; font-size: 12px;">Current User</span>
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

        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableRows = document.querySelectorAll('#userTable tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value;

            tableRows.forEach(row => {
                if (row.querySelector('.no-data')) return;

                const text = row.textContent.toLowerCase();
                const role = row.dataset.role;

                const matchesSearch = text.includes(searchTerm);
                const matchesRole = !selectedRole || role === selectedRole;

                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    </script>
</body>
</html>