<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f5f5f5; 
        }
        
        .header { 
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); 
            color: white; 
            padding: 20px 40px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 40px;
        }
        
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1e3a5f;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #2c5282;
            transform: translateY(-2px);
        }
        
        .meeting-info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-left: 4px solid #1e3a5f;
        }
        
        .meeting-title {
            font-size: 22px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 15px;
        }
        
        .meeting-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            font-size: 14px;
            color: #666;
        }
        
        .meta-item strong {
            color: #1a1a1a;
            display: block;
            margin-bottom: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table-header {
            background: #1e3a5f;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .table-title {
            font-size: 18px;
            font-weight: 600;
        }
        
        .export-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-export {
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .btn-export:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }
        
        .btn-export svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
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
        
        .badge-present {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .badge-pending {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        
        .no-data h3 {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .export-buttons {
                width: 100%;
                flex-direction: column;
            }
            
            .btn-export {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Meeting Attendance Report</h1>
        <div class="header-subtitle">View and manage attendance for this meeting</div>
    </div>

    <div class="container">
        <a href="<?= base_url('meeting') ?>" class="back-btn">← Back to Meetings</a>

        <!-- Meeting Info Card -->
        <div class="meeting-info-card">
            <div class="meeting-title"><?= htmlspecialchars($meeting['topic']) ?></div>
            <div class="meeting-meta">
                <div class="meta-item">
                    <strong>Date & Time</strong>
                    <?= date('l, F j, Y', strtotime($meeting['meeting_date'])) ?><br>
                    <?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?> WIB
                </div>
                <div class="meta-item">
                    <strong>Duration</strong>
                    <?= $meeting['duration'] ?> minutes
                </div>
                <div class="meta-item">
                    <strong>Organizer</strong>
                    <?= htmlspecialchars($meeting['requester_name']) ?>
                </div>
                <div class="meta-item">
                    <strong>Location</strong>
                    <?php if($meeting['room_name']): ?>
                        <?= htmlspecialchars($meeting['room_name']) ?>
                    <?php elseif($meeting['platform_name']): ?>
                        <?= htmlspecialchars($meeting['platform_name']) ?>
                    <?php else: ?>
                        Online Meeting
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-title">Attendance List (<?= count($attendance_list) ?>)</div>
                
                <div class="export-buttons">
                    <a href="<?= base_url('attendance/export_excel/' . $meeting['id']) ?>" 
                       class="btn-export" 
                       title="Export to Excel">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12.9,14.5L15.8,19H14L12,15.6L10,19H8.2L11.1,14.5L8.2,10H10L12,13.4L14,10H15.8L12.9,14.5Z"/>
                        </svg>
                        Export Excel
                    </a>
                    
                    <a href="<?= base_url('attendance/export_pdf/' . $meeting['id']) ?>" 
                       class="btn-export" 
                       target="_blank"
                       title="Export to PDF">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M10.5,11.5C10.5,10.67 11.17,10 12,10C12.83,10 13.5,10.67 13.5,11.5V16.5C13.5,17.33 12.83,18 12,18C11.17,18 10.5,17.33 10.5,16.5V11.5Z"/>
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>

            <?php if(empty($attendance_list)): ?>
            <div class="no-data">
                <h3>No Attendance Records</h3>
                <p>No one has filled out the attendance form yet</p>
            </div>
            <?php else: ?>
            <table id="attendanceTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Marked At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($attendance_list as $att): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($att['name']) ?></strong></td>
                        <td><?= htmlspecialchars($att['company']) ?></td>
                        <td><?= htmlspecialchars($att['position']) ?></td>
                        <td><?= htmlspecialchars($att['email']) ?></td>
                        <td><?= htmlspecialchars($att['phone']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($att['marked_at'])) ?></td>
                        <td>
                            <span class="badge badge-<?= $att['status'] ?>">
                                <?= ucfirst($att['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>