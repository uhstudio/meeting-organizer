<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - PT TJB Power Services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: #ffffff;
            padding: 10px;
            border-bottom: 3px solid #0066cc;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 150px;
            height: 80px;
            object-fit: contain;
        }

        .header-title h1 {
            font-size: 22px;
            color: #1a1a1a;
            font-weight: 700;
        }

        .meeting-info {
            background: #f8f9fa;
            padding: 25px 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            font-size: 14px;
        }

        .info-label {
            width: 120px;
            color: #666;
            font-weight: 500;
        }

        .info-value {
            flex: 1;
            color: #1a1a1a;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-online { 
            background: #e8f4f8; 
            color: #0066cc; 
        }

        .badge-offline { 
            background: #fff4e6; 
            color: #cc6600; 
        }

        .badge-hybrid { 
            background: #f3e8ff; 
            color: #6600cc; 
        }

        .form-section {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 8px;
            font-weight: 500;
        }

        label .required {
            color: #dc3545;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #0066cc;
        }

        input::placeholder {
            color: #999;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #dc3545;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #ffc107;
            color: #000;
        }

        .btn-cancel:hover {
            background: #e0a800;
        }

        .btn-submit {
            background: #28a745;
            color: white;
        }

        .btn-submit:hover {
            background: #218838;
        }

        .footer-note {
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="<?= base_url('assets/images/tjb-logo.png') ?>" class="logo" alt="Logo TJB">
            <div class="header-title">
                <h1>Meeting Attendance</h1>
            </div>
        </div>

        <!-- Meeting Info -->
        <div class="meeting-info">
            <div class="info-row">
                <span class="info-label">DAY/DATE</span>
                <span class="info-value">: <?= date('D, d-M-Y', strtotime($meeting['meeting_date'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">VENUE</span>
                <span class="info-value">: <span class="badge badge-<?= $meeting['meeting_type'] ?>"><?= ucfirst($meeting['meeting_type']) ?> Meeting</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">AGENDA</span>
                <span class="info-value">: <?= htmlspecialchars($meeting['topic']) ?></span>
            </div>
        </div>

        <!-- Form -->
        <div class="form-section">
            <?php if(isset($errors) && $errors): ?>
            <div class="error-message">
                <?= $errors ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('attendance/submit') ?>">
                <input type="hidden" name="meeting_id" value="<?= $meeting['id'] ?>">

                <div class="form-group">
                    <label for="name">Name<span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="<?= set_value('name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="company">Company<span class="required">*</span></label>
                    <input type="text" id="company" name="company" value="<?= set_value('company') ?>" required>
                </div>

                <div class="form-group">
                    <label for="position">Position<span class="required">*</span></label>
                    <input type="text" id="position" name="position" value="<?= set_value('position') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email<span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="<?= set_value('email') ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number<span class="required">*</span></label>
                    <input type="text" id="phone" name="phone" placeholder="08xx xxxx xxx" value="<?= set_value('phone') ?>" required>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <button type="button" class="btn btn-cancel" onclick="window.location.href='<?= base_url() ?>'">
                Cancel
            </button>
            <button type="submit" class="btn btn-submit" onclick="document.querySelector('form').submit()">
                Save
            </button>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            PT TJB Power Services - Meeting Management System<br>
            Please fill in all required fields to complete your attendance
        </div>
    </div>
</body>
</html>