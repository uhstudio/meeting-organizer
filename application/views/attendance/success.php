<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - PT TJB Power Services</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #1e3a5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #2047a9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease-out;
        }
        
        .success-icon svg {
            width: 45px;
            height: 45px;
            stroke: white;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            animation: checkmark 0.5s ease-out 0.3s forwards;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        @keyframes checkmark {
            to { stroke-dashoffset: 0; }
        }
        
        h1 {
            font-size: 28px;
            color: #1a1a1a;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 35px;
            line-height: 1.6;
        }
        
        .meeting-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 600;
            text-align: right;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .footer-note {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <svg viewBox="0 0 52 52">
                <polyline points="14 27 22 35 38 19"></polyline>
            </svg>
        </div>
        
        <h1>✓ Attendance Confirmed</h1>
        
        <?php if ($already_marked): ?>
            <span class="badge badge-warning">Already Marked</span>
            <p class="message">Your attendance was previously recorded.</p>
        <?php else: ?>
            <span class="badge badge-success">Present</span>
            <p class="message">Thank you! Your attendance has been successfully recorded.</p>
        <?php endif; ?>
        
        <div class="meeting-details">
            <div class="detail-row">
                <span class="detail-label">Meeting</span>
                <span class="detail-value"><?= htmlspecialchars($meeting['topic']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value"><?= htmlspecialchars($meeting['name'] ?? $meeting['full_name']) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value"><?= date('l, F j, Y', strtotime($meeting['meeting_date'])) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value"><?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?> WIB</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Marked At</span>
                <span class="detail-value"><?= date('H:i:s, d M Y', strtotime($meeting['marked_at'])) ?></span>
            </div>
        </div>
        
        <a href="<?= base_url() ?>" class="btn">Back to Home</a>
        
        <p class="footer-note">
            PT TJB Power Services - Meeting Management System
        </p>
    </div>
</body>
</html>