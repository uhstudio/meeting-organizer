<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            max-width: 900px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-header svg {
            width: 32px;
            height: 32px;
            color: #1e3a5f;
        }

        .form-header h2 {
            font-size: 24px;
            color: #333;
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 5px;
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

        select.form-control {
            cursor: pointer;
        }

        .custom-select-wrapper {
            position: relative;
        }

        .custom-select-input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            background: white;
            transition: all 0.3s;
        }

        .custom-select-input:focus {
            outline: none;
            border-color: #1e3a5f;
            box-shadow: 0 0 0 3px rgba(30,58,95,0.1);
        }

        .custom-select-arrow {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #666;
        }

        .custom-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .custom-dropdown.show {
            display: block;
        }

        .dropdown-search {
            position: sticky;
            top: 0;
            background: white;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .dropdown-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .dropdown-search input:focus {
            outline: none;
            border-color: #1e3a5f;
        }

        .dropdown-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-item:hover {
            background: #f5f5f5;
        }

        .dropdown-item.selected {
            background: #e3f2fd;
            color: #1e3a5f;
            font-weight: 600;
        }

        .dropdown-item-name {
            font-weight: 500;
            color: #333;
        }

        .dropdown-item-position {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        .dropdown-no-results {
            padding: 20px;
            text-align: center;
            color: #999;
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
            min-height: 20px;
        }

        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1e3a5f;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 13px;
        }

        .selected-tag-remove {
            cursor: pointer;
            font-weight: bold;
            padding: 0 4px;
        }

        .selected-tag-remove:hover {
            color: #ff5252;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .checkbox-item:hover {
            background: #f5f5f5;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-item-content {
            flex: 1;
        }

        .checkbox-item-name {
            font-weight: 500;
            color: #333;
        }

        .checkbox-item-position {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }
        
        select.form-control {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 35px;
        }

        select.form-control optgroup {
            font-weight: 700;
            font-style: normal;
            color: #1e3a5f;
            background: #f0f4f8;
            padding: 8px 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        select.form-control optgroup option {
            font-weight: 400;
            color: #333;
            background: white;
            padding: 10px 20px;
            font-size: 14px;
            text-transform: none;
            letter-spacing: normal;
        }

        select.form-control option:hover {
            background: #e3f2fd;
            color: #1e3a5f;
        }

        select.form-control option:checked {
            background: #1e3a5f;
            color: white;
        }

        .platform-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }

    </style>
</head>
<body>

<div class="header">
    <div class="header-content">
        <a href="<?= base_url('meeting') ?>" class="back-btn">← Back</a>
        <div class="header-title">Create Meeting</div>
    </div>
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
            <ul>
                <?php foreach($this->session->flashdata('errors') as $err): ?>
                    <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= base_url('meeting/store') ?>" method="post" id="meetingForm">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <input type="hidden" name="requested_by" id="requestedById">

            <div class="form-group">
                <label class="form-label">Topic <span class="required">*</span></label>
                <input type="text" name="topic" class="form-control" required value="<?= set_value('topic') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"><?= set_value('description') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Meeting Type</label>
                <div class="radio-group">
                    <label><input type="radio" name="meeting_type" value="online" checked> Online</label>
                    <label><input type="radio" name="meeting_type" value="offline"> Offline</label>
                    <label><input type="radio" name="meeting_type" value="hybrid"> Hybrid</label>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label class="form-label">Date</label>
                    <input type="date" name="meeting_date" class="form-control" value="<?= set_value('meeting_date', date('Y-m-d')) ?>" required>
                </div>
                <div>
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" id="startTimeInput" required>
                </div>
                <div>
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" name="duration" class="form-control" value="<?= set_value('duration',60) ?>" required>
                </div>
            </div>

            <!-- ONLINE -->
            <div id="onlineFields">
                <div class="form-group">
                    <label class="form-label">Platform</label>
                    <select name="platform_id" class="form-control">
                        <option value="">-- Select Platform --</option>
                        <?php foreach($platforms_grouped as $type => $platforms): ?>
                            <optgroup label="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">
                                <?php foreach($platforms as $p): ?>
                                    <option value="<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['platform_name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Meeting Link</label>
                    <input type="url" name="meeting_link" class="form-control" value="<?= set_value('meeting_link') ?>">
                </div>
                
                <!--                   
                <div class="form-group">
                    <label class="form-label">Passcode</label>
                    <input type="text" name="passcode" class="form-control" value="<?= set_value('passcode') ?>">
                </div>
                -->

            </div>

            <!-- OFFLINE -->
            <div id="offlineFields" style="display:none">
                <div class="form-group">
                    <label class="form-label">Meeting Room</label>
                    <select name="room_id" class="form-control">
                        <option value="">-- Select Room --</option>
                        <?php foreach($rooms as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['room_name'], ENT_QUOTES, 'UTF-8') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- REQUESTED BY -->
            <div class="form-group">
                <label class="form-label">Requested By <span class="required">*</span></label>
                <div class="custom-select-wrapper">
                    <input type="text" 
                           class="custom-select-input" 
                           id="requestedByInput" 
                           placeholder="Select person who requests this meeting"
                           readonly
                           required>
                    <span class="custom-select-arrow">▼</span>
                    <div class="custom-dropdown" id="requestedByDropdown">
                        <div class="dropdown-search">
                            <input type="text" 
                                   id="requestedBySearch" 
                                   placeholder="Search by name or position...">
                        </div>
                        <div id="requestedByList">
                            <?php foreach($users as $u): ?>
                                <div class="dropdown-item" 
                                     data-id="<?= $u['id'] ?>"
                                     data-name="<?= htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8') ?>"
                                     data-position="<?= htmlspecialchars($u['position'] ?: 'No Position', ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="dropdown-item-name"><?= htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <div class="dropdown-item-position"><?= htmlspecialchars($u['position'] ?: 'No Position', ENT_QUOTES, 'UTF-8') ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PARTICIPANTS -->
            <div class="form-group">
                <label class="form-label">Participants</label>
                <div class="custom-select-wrapper">
                    <input type="text" 
                           class="custom-select-input" 
                           id="participantsInput" 
                           placeholder="Click to select participants"
                           readonly>
                    <span class="custom-select-arrow">▼</span>
                    <div class="custom-dropdown" id="participantsDropdown">
                        <div class="dropdown-search">
                            <input type="text" 
                                   id="participantsSearch" 
                                   placeholder="Search by name or position...">
                        </div>
                        <div id="participantsList">
                            <?php foreach($users as $u): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" 
                                           name="participants[]" 
                                           value="<?= $u['id'] ?>"
                                           data-name="<?= htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8') ?>"
                                           data-position="<?= htmlspecialchars($u['position'] ?: 'No Position', ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="checkbox-item-content">
                                        <div class="checkbox-item-name"><?= htmlspecialchars($u['full_name'], ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="checkbox-item-position"><?= htmlspecialchars($u['position'] ?: 'No Position', ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="selected-items" id="selectedParticipants"></div>
            </div>

            <div style="margin-top:20px;text-align:right">
                <a href="<?= base_url('meeting') ?>" class="btn btn-secondary">Cancel</a>
                <button class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    const radios = document.querySelectorAll('input[name="meeting_type"]');
    const online = document.getElementById('onlineFields');
    const offline = document.getElementById('offlineFields');

    function toggleMeetingType() {
        const v = document.querySelector('input[name="meeting_type"]:checked').value;
        online.style.display = (v !== 'offline') ? 'block' : 'none';
        offline.style.display = (v !== 'online') ? 'block' : 'none';
    }
    radios.forEach(r => r.addEventListener('change', toggleMeetingType));
    toggleMeetingType();

    const requestedByInput = document.getElementById('requestedByInput');
    const requestedByDropdown = document.getElementById('requestedByDropdown');
    const requestedBySearch = document.getElementById('requestedBySearch');
    const requestedByList = document.getElementById('requestedByList');
    const requestedById = document.getElementById('requestedById');

    requestedByInput.addEventListener('click', function(e) {
        e.stopPropagation();
        requestedByDropdown.classList.toggle('show');
        participantsDropdown.classList.remove('show');
        if (requestedByDropdown.classList.contains('show')) {
            requestedBySearch.focus();
        }
    });

    requestedBySearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = requestedByList.querySelectorAll('.dropdown-item');
        let hasResults = false;

        items.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const position = item.dataset.position.toLowerCase();
            if (name.includes(searchTerm) || position.includes(searchTerm)) {
                item.style.display = 'block';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });

        let noResults = requestedByList.querySelector('.dropdown-no-results');
        if (!hasResults) {
            if (!noResults) {
                noResults = document.createElement('div');
                noResults.className = 'dropdown-no-results';
                noResults.textContent = 'No results found';
                requestedByList.appendChild(noResults);
            }
            noResults.style.display = 'block';
        } else if (noResults) {
            noResults.style.display = 'none';
        }
    });

    requestedByList.addEventListener('click', function(e) {
        const item = e.target.closest('.dropdown-item');
        if (item) {
            requestedByList.querySelectorAll('.dropdown-item').forEach(i => {
                i.classList.remove('selected');
            });

            item.classList.add('selected');

            const name = item.dataset.name;
            const position = item.dataset.position;
            requestedByInput.value = `${name} - ${position}`;
            requestedById.value = item.dataset.id;

            autoCheckRequester(item.dataset.id);

            requestedByDropdown.classList.remove('show');
            requestedBySearch.value = '';
            
            requestedByList.querySelectorAll('.dropdown-item').forEach(i => {
                i.style.display = 'block';
            });
        }
    });

    const participantsInput = document.getElementById('participantsInput');
    const participantsDropdown = document.getElementById('participantsDropdown');
    const participantsSearch = document.getElementById('participantsSearch');
    const participantsList = document.getElementById('participantsList');
    const selectedParticipants = document.getElementById('selectedParticipants');

    participantsInput.addEventListener('click', function(e) {
        e.stopPropagation();
        participantsDropdown.classList.toggle('show');
        requestedByDropdown.classList.remove('show');
        if (participantsDropdown.classList.contains('show')) {
            participantsSearch.focus();
        }
    });

    participantsSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = participantsList.querySelectorAll('.checkbox-item');
        let hasResults = false;

        items.forEach(item => {
            const checkbox = item.querySelector('input[type="checkbox"]');
            const name = checkbox.dataset.name.toLowerCase();
            const position = checkbox.dataset.position.toLowerCase();
            if (name.includes(searchTerm) || position.includes(searchTerm)) {
                item.style.display = 'flex';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });
    });

    function updateSelectedParticipants() {
        const checkboxes = participantsList.querySelectorAll('input[type="checkbox"]:checked');
        selectedParticipants.innerHTML = '';

        checkboxes.forEach(checkbox => {
            const tag = document.createElement('div');
            tag.className = 'selected-tag';
            tag.innerHTML = `
                ${checkbox.dataset.name}
                <span class="selected-tag-remove" data-id="${checkbox.value}">×</span>
            `;
            selectedParticipants.appendChild(tag);
        });

        if (checkboxes.length > 0) {
            participantsInput.value = `${checkboxes.length} participant(s) selected`;
        } else {
            participantsInput.value = '';
        }
    }

    participantsList.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            updateSelectedParticipants();
        }
    });

    selectedParticipants.addEventListener('click', function(e) {
        if (e.target.classList.contains('selected-tag-remove')) {
            const id = e.target.dataset.id;
            const checkbox = participantsList.querySelector(`input[value="${id}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedParticipants();
            }
        }
    });

    function autoCheckRequester(userId) {
        const checkbox = participantsList.querySelector(`input[value="${userId}"]`);
        if (checkbox && !checkbox.checked) {
            checkbox.checked = true;
            updateSelectedParticipants();
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            requestedByDropdown.classList.remove('show');
            participantsDropdown.classList.remove('show');
        }
    });

    requestedBySearch.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    participantsSearch.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    updateSelectedParticipants();

    (function() {
        const preselectedRoom = '<?= isset($preselected_room) ? $preselected_room : "" ?>';
        const preselectedDate = '<?= isset($preselected_date) ? $preselected_date : "" ?>';

        if (preselectedDate) {
            const dateInput = document.querySelector('input[name="meeting_date"]');
            if (dateInput) {
                dateInput.value = preselectedDate;
            }
        }

        if (preselectedRoom) {
            const offlineRadio = document.querySelector('input[name="meeting_type"][value="offline"]');
            if (offlineRadio) {
                offlineRadio.checked = true;
                toggleMeetingType();
            }

            const roomSelect = document.querySelector('select[name="room_id"]');
            if (roomSelect) {
                roomSelect.value = preselectedRoom;
                
                roomSelect.style.border = '2px solid #4caf50';
                setTimeout(() => {
                    roomSelect.style.border = '1px solid #ddd';
                }, 2000);
            }

            const formCard = document.querySelector('.form-card');
            const notification = document.createElement('div');
            notification.className = 'alert alert-success';
            notification.style.marginBottom = '20px';
            notification.innerHTML = '✓ Room and date have been pre-selected for you!';
            formCard.insertBefore(notification, formCard.firstChild);

            setTimeout(() => {
                notification.style.transition = 'opacity 0.5s';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }
    })();

    function setCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentTime = `${hours}:${minutes}`;

        const startTimeInput = document.getElementById('startTimeInput');

        if (!startTimeInput.value) {
            startTimeInput.value = currentTime;
        }
    }

    setCurrentTime();

    setInterval(setCurrentTime, 30000);

    </script>

</body>
</html>