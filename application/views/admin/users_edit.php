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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .back-btn {
            color: white;
            text-decoration: none;
            font-size: 24px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .header-title {
            font-size: 24px;
            font-weight: bold;
        }
        
        .container {
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #2196f3;
        }
        
        .info-box strong {
            color: #1976d2;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-label .required {
            color: #f44336;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1e3a5f;
            box-shadow: 0 0 0 3px rgba(30,58,95,0.1);
        }
        
        .form-control:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
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
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        
        .form-hint {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle input {
            padding-right: 45px;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 12px;
            user-select: none;
        }
        
        .section-divider {
            height: 2px;
            background: #f0f0f0;
            margin: 30px 0;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <a href="<?= base_url('admin/users') ?>" class="back-btn">← Back</a>
            <div class="header-title">Edit User</div>
        </div>
    </div>

    <div class="container">
        <?php if($this->session->flashdata('errors')): ?>
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                <?php foreach($this->session->flashdata('errors') as $error): ?>
                <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-error">
            <?= $this->session->flashdata('error') ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="info-box">
                <strong>Username:</strong> <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                <div class="form-hint">Username cannot be changed</div>
            </div>

            <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                <div class="section-title">Basic Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Employee ID</label>
                        <input type="text" 
                               name="employee_id" 
                               class="form-control" 
                               value="<?php 
                                   $old = $this->session->flashdata('old_input');
                                   echo $old ? htmlspecialchars($old['employee_id'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($user['employee_id'], ENT_QUOTES, 'UTF-8');
                               ?>" 
                               placeholder="260111001">
                        <div class="form-hint">Optional</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Position</label>
                        <input type="text" 
                               name="position" 
                               class="form-control" 
                               value="<?php 
                                   $old = $this->session->flashdata('old_input');
                                   echo $old ? htmlspecialchars($old['position'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($user['position'], ENT_QUOTES, 'UTF-8');
                               ?>" 
                               placeholder="IT Manager">
                        <div class="form-hint">Optional</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" 
                           name="full_name" 
                           class="form-control" 
                           value="<?php 
                               $old = $this->session->flashdata('old_input');
                               echo $old ? htmlspecialchars($old['full_name'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8');
                           ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           value="<?php 
                               $old = $this->session->flashdata('old_input');
                               echo $old ? htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
                           ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control" required>
                        <?php 
                            $old = $this->session->flashdata('old_input');
                            $selected_role = $old ? $old['role'] : $user['role'];
                        ?>
                        <option value="admin" <?= $selected_role === 'admin' ? 'selected' : '' ?>>Administrator</option>
                        <option value="user" <?= $selected_role === 'user' ? 'selected' : '' ?>>Regular User</option>
                    </select>
                </div>

                <div class="section-divider"></div>
                
                <div class="section-title">Change Password (Optional)</div>
                <div class="form-hint" style="margin-bottom: 15px;">Leave blank if you don't want to change the password</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                            <span class="toggle-password" onclick="togglePassword('password')">Show</span>
                        </div>
                        <div class="form-hint">Minimum 6 characters</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-toggle">
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="••••••••">
                            <span class="toggle-password" onclick="togglePassword('password_confirm')">Show</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 30px; text-align: right;">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const toggle = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggle.textContent = 'Show';
            }
        }
    </script>
</body>
</html>