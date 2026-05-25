<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Invitation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
            line-height: 1.6;
        }
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #ffffff;
            padding: 40px 40px 20px;
            border-bottom: 3px solid #0066cc;
        }
        .company-logo {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 5px 0;
        }
        .header-subtitle {
            color: #666666;
            font-size: 14px;
            margin: 0;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 16px;
            color: #1a1a1a;
            margin: 0 0 25px 0;
        }
        .meeting-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 8px 0;
        }
        .meeting-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 30px;
        }
        .type-online { background-color: #e8f4f8; color: #0066cc; }
        .type-offline { background-color: #fff4e6; color: #cc6600; }
        .type-hybrid { background-color: #f3e8ff; color: #6600cc; }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .details-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e8e8e8;
        }
        .details-table td:first-child {
            color: #666666;
            font-size: 14px;
            width: 140px;
            vertical-align: top;
        }
        .details-table td:last-child {
            color: #1a1a1a;
            font-size: 14px;
            font-weight: 500;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        
        
        .meeting-link-section {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .meeting-link-section p {
            margin: 0 0 15px 0;
            color: #666666;
            font-size: 14px;
        }
        .btn-join {
            display: inline-block;
            background-color: #0066cc;
            color: #ffffff !important;
            padding: 12px 32px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn-join:hover {
            background-color: #0052a3;
        }
        .passcode-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #666666;
        }
        .passcode-info strong {
            color: #1a1a1a;
            font-family: 'Courier New', monospace;
        }
        .description-section {
            margin: 30px 0;
        }
        .description-section .label {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .description-section .text {
            font-size: 14px;
            color: #444444;
            line-height: 1.7;
            padding: 15px;
            background-color: #fafafa;
            border-radius: 4px;
        }

        .attendance-link {
            display: inline-block;
            color: #0029cb !important;
            font-size: 14px;
            text-decoration: underline;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        
        .attendance-link:hover {
            opacity: 0.8;
        }

        .attendance-note {
            color: rgba(255,255,255,0.9);
            font-size: 12px;
            margin-top: 12px;
            font-style: italic;
        }

        .footer-note {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e8e8e8;
            font-size: 13px;
            color: #999999;
            text-align: center;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            color: #666666;
            font-size: 12px;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .email-footer .company-name {
            font-weight: 600;
            color: #1a1a1a;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <div class="company-logo">PT TJB Power Services</div>
            <div class="header-subtitle">Meeting Management System</div>
        </div>

        <div class="content">
            <p class="greeting">Dear Team Member,</p>
            
            <div class="meeting-title"><?= htmlspecialchars($meeting['topic'], ENT_QUOTES, 'UTF-8') ?></div>
            <span class="meeting-type type-<?= $meeting['meeting_type'] ?>">
                <?= ucfirst($meeting['meeting_type']) ?> Meeting
            </span>

            <table class="details-table">
                <tr>
                    <td>Date</td>
                    <td><?= date('l, F j, Y', strtotime($meeting['meeting_date'])) ?></td>
                </tr>
                <tr>
                    <td>Time</td>
                    <td><?= date('H:i', strtotime($meeting['start_time'])) ?> - <?= date('H:i', strtotime($meeting['end_time'])) ?> WIB</td>
                </tr>
                <tr>
                    <td>Duration</td>
                    <td><?= $meeting['duration'] ?> minutes</td>
                </tr>
                <tr>
                    <td>Organizer</td>
                    <td><?= htmlspecialchars($meeting['requester_name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php if($meeting['room_name']): ?>
                <tr>
                    <td>Location</td>
                    <td><?= htmlspecialchars($meeting['room_name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endif; ?>
                <?php if($meeting['platform_name']): ?>
                <tr>
                    <td>Platform</td>
                    <td><?= htmlspecialchars($meeting['platform_name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <?php endif; ?>
            </table>

            <?php if(($meeting['meeting_type'] === 'online' || $meeting['meeting_type'] === 'hybrid') && $meeting['meeting_link']): ?>
            <div class="meeting-link-section">
                <p>Join the meeting using the link below:</p>
                <a href="<?= htmlspecialchars($meeting['meeting_link'], ENT_QUOTES, 'UTF-8') ?>" class="btn-join" target="_blank">
                    Join Meeting
                </a>
                <?php if($meeting['passcode']): ?>
                <div class="passcode-info">
                    Meeting Passcode: <strong><?= htmlspecialchars($meeting['passcode'], ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($meeting['description']): ?>
            <div class="description-section">
                <div class="label">Meeting Description</div>
                <div class="text">
                    <?= nl2br(htmlspecialchars($meeting['description'], ENT_QUOTES, 'UTF-8')) ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="footer-note">
                Please confirm your attendance and ensure you are prepared for this meeting.
            <a href="<?= $meeting['attendance_link'] ?>" class="attendance-link" target="_blank">
                 Click here to mark your attendance
            </a>
            <p class="attendance-note">
                Available from <?= date('H:i', strtotime($meeting['start_time'])) ?> WIB until 23:59 WIB
            </p>
            </div>
        </div>

        <?php if(isset($meeting['attendance_link'])): ?>
        <div class="attendance-section">
            
        </div>
        <?php endif; ?>
            
        <div class="email-footer">
            <p class="company-name">PT TJB Power Services</p>
            <p>Tanjung Jati B Coal Fired Power Plant Ds. Tubanan, Kembang, Jepara 59457 Central Java - Indonesia</p>
            <p style="margin-top: 15px; color: #999999;">
                This is an automated notification. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>