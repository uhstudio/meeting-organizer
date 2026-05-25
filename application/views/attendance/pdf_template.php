<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Meeting Attendance Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 15px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
        }

        .meeting-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #1e3a5f;
        }

        .meeting-info h2 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #1e3a5f;
        }

        .info-row {
            margin: 5px 0;
            font-size: 11px;
        }

        .info-row strong {
            display: inline-block;
            width: 120px;
            color: #666;
        }

        .statistics {
            margin: 20px 0;
            display: table;
            width: 100%;
        }

        .stat-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .attendance-table thead {
            background: #1e3a5f;
            color: white;
        }

        .attendance-table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }

        .attendance-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .attendance-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .attendance-table td {
            padding: 8px;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-present {
            background: #e8f5e9;
            color: #388e3c;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 9px;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="company-name">PT TJB POWER SERVICES</div>
            <div class="report-title">MEETING ATTENDANCE REPORT</div>
        </div>

        <!-- Meeting Info -->
        <div class="meeting-info">
            <h2><?= htmlspecialchars($meeting['topic']) ?></h2>
            <div class="info-row">
                <strong>Date:</strong>
                <?= date('l, F j, Y', strtotime($meeting['meeting_date'])) ?>
            </div>
            <div class="info-row">
                <strong>Time:</strong>
                <?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?> WIB
            </div>
            <div class="info-row">
                <strong>Duration:</strong>
                <?= $meeting['duration'] ?> minutes
            </div>
            <div class="info-row">
                <strong>Organizer:</strong>
                <?= htmlspecialchars($meeting['requester_name']) ?>
            </div>
            <div class="info-row">
                <strong>Location:</strong>
                <?php if($meeting['room_name']): ?>
                    <?= htmlspecialchars($meeting['room_name']) ?>
                <?php elseif($meeting['platform_name']): ?>
                    <?= htmlspecialchars($meeting['platform_name']) ?>
                <?php else: ?>
                    Online Meeting
                <?php endif; ?>
            </div>
        </div>

        <!-- Attendance Table -->
        <?php if(!empty($attendance_list)): ?>
        <table class="attendance-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Name</th>
                    <th width="15%">Company</th>
                    <th width="15%">Position</th>
                    <th width="20%">Email</th>
                    <th width="12%">Phone</th>
                    <th width="13%">Marked At</th>
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
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #999;">No attendance records found</p>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p>Generated on <?= date('l, F j, Y H:i:s') ?> WIB</p>
            <p>PT TJB Power Services - Meeting Management System</p>
        </div>
    </div>
</body>
</html>