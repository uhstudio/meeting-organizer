<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($room['room_name'], ENT_QUOTES, 'UTF-8') ?> - Display</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            height: 100vh;
            background: #1e3a8a;
        }

        .display-wrapper {
            height: 100vh;
            position: relative;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .background-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('<?= base_url('assets/images/tjb-background.jpg') ?>');
            background-size: cover;
            background-position: center;
            z-index: 0;
        }

        .background-layer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.92) 0%, rgba(30, 64, 175, 0.88) 100%);
            z-index: 1;
        }

        .content-layer {
            position: relative;
            z-index: 2;
            height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 25px 40px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            gap: 40px;
        }

        .header-left {
            display: flex;
            flex-direction: column;
        }

        .header-date {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .header-time {
            font-size: 20px;
            font-weight: 600;
            color: #bfdbfe;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .header-center {
            text-align: center;
        }

        .company-name {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .system-label {
            font-size: 14px;
            color: #bfdbfe;
            font-weight: 500;
        }

        .header-right {
            width: 150px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .header-logo {
            height: 80px;
            max-width: 150px;
            object-fit: contain;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 0;
            height: 100%;
            overflow: hidden;
        }

        .left-section {
            display: grid;
            grid-template-rows: auto 1fr;
            gap: 0;
            padding: 30px;
            padding-right: 15px;
        }

        .room-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .room-name {
            font-size: 36px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.1;
        }

        .room-info {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .meeting-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .meeting-card::-webkit-scrollbar {
            width: 6px;
        }

        .meeting-card::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .meeting-card::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .card-title {
            font-size: 11px;
            font-weight: 700;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 18px;
        }

        .card-title.ongoing {
            color: #10b981;
        }

        .card-title.no-meeting {
            color: #f59e0b;
        }

        .meeting-topic {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 24px;
            line-height: 1.3;
        }

        .no-meeting-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
        }

        .no-meeting-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .no-meeting-title {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .no-meeting-subtitle {
            font-size: 16px;
            color: #64748b;
            font-weight: 500;
        }

        .info-grid {
            display: grid;
            gap: 14px;
            margin-bottom: 24px;
        }

        .info-item {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.4;
        }

        .meeting-type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-online {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-offline {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-hybrid {
            background: #e9d5ff;
            color: #6b21a8;
        }

        .schedule-section {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            padding: 30px;
            display: flex;
            flex-direction: column;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }

        .schedule-title {
            font-size: 11px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .schedule-list {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 8px;
        }

        .schedule-list::-webkit-scrollbar {
            width: 4px;
        }

        .schedule-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .schedule-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .schedule-item {
            background: rgba(255, 255, 255, 0.08);
            border-left: 3px solid rgba(255, 255, 255, 0.5);
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .schedule-item.active {
            background: rgba(16, 185, 129, 0.25);
            border-left-color: #10b981;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
        }

        .schedule-time {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .schedule-topic {
            font-size: 12px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 5px;
        }

        .schedule-organizer {
            font-size: 10px;
            color: #cbd5e1;
            font-weight: 500;
        }

        .schedule-item .progress-wrapper {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .schedule-item .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .schedule-item .progress-label {
            font-size: 9px;
            font-weight: 700;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .schedule-item .progress-time {
            font-size: 9px;
            font-weight: 700;
            color: #10b981;
            font-family: 'Courier New', monospace;
        }

        .schedule-item .progress-bar-container {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
        }

        .schedule-item .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
            border-radius: 10px;
            transition: width 1s linear;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
            padding: 40px 20px;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 6px;
        }

        .empty-subtitle {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.5);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #1e3a8a;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.hide {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .meeting-card-content {
            transition: opacity 0.2s ease;
        }

        .meeting-card-content.updating {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="display-wrapper">
        <div class="background-layer"></div>

        <div class="content-layer">
            <!-- HEADER -->
            <div class="header">
                <div class="header-left">
                    <div class="header-date" id="headerDate">20 Jan 2026</div>
                    <div class="header-time" id="headerTime">11 : 25 : 42</div>
                </div>
                
                <div class="header-center">
                    <div class="company-name">PT TJB POWER SERVICES</div>
                    <div class="system-label">Meeting Organizer</div>
                </div>
                
                <div class="header-right">
                    <img src="<?= base_url('assets/images/tjb-logo.png') ?>" alt="Logo TJB" class="header-logo">
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <div class="left-section">
                    <div class="room-card">
                        <div class="room-name"><?= htmlspecialchars($room['room_name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="room-info">Capacity: <?= $room['capacity'] ?> persons</div>
                    </div>

                    <div class="meeting-card">
                        <div class="meeting-card-content" id="meetingCardContent">
                            <div class="no-meeting-container">
                                <div class="no-meeting-icon">⏳</div>
                                <div class="no-meeting-title">Loading...</div>
                                <div class="no-meeting-subtitle">Please wait</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="schedule-section">
                    <div class="schedule-title">Next meeting</div>
                    <div class="schedule-list" id="scheduleList">
                        <div class="empty-state">
                            <div class="empty-title">No schedule</div>
                            <div class="empty-subtitle">No meetings scheduled</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== CONFIGURATION =====
        const ROOM_ID = <?= $room_id ?>;
        const BASE_URL = '<?= base_url() ?>';
        const REFRESH_INTERVAL = 10000; 
        const CLOCK_INTERVAL = 1000; 
        const COUNTDOWN_INTERVAL = 1000; 
        
        // ===== GLOBAL STATE =====
        let currentMeetingData = null;
        let upcomingMeetingsData = [];
        let countdownInterval = null;
        

        function updateDateTime() {
            const now = new Date();
            
            const day = now.getDate();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            document.getElementById('headerDate').textContent = `${day} ${month} ${year}`;
            
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('headerTime').textContent = `${hours} : ${minutes} : ${seconds}`;
        }


        function updateCountdown() {
            if (!currentMeetingData) return;
            
            const progressTimeElement = document.querySelector('.schedule-item.active .progress-time');
            const progressBarElement = document.querySelector('.schedule-item.active .progress-bar');
            
            if (progressTimeElement && progressBarElement) {
                const timeRemaining = calculateTimeRemainingDetailed(currentMeetingData.end_time_full);
                const progress = calculateProgress(currentMeetingData.start_time_full, currentMeetingData.end_time_full);
                
                progressTimeElement.textContent = timeRemaining;
                progressBarElement.style.width = progress + '%';
            }
        }


        function startCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            if (currentMeetingData) {
                countdownInterval = setInterval(updateCountdown, COUNTDOWN_INTERVAL);
            }
        }


        function stopCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        }

        async function fetchMeetingStatus() {
            try {
                const response = await fetch(`${BASE_URL}display/api_meeting_status/${ROOM_ID}`);
                const data = await response.json();
                
                if (data.success) {
                    currentMeetingData = data.current_meeting;
                    upcomingMeetingsData = data.upcoming_meetings || [];
                    
                    updateMeetingCardSmooth(data.status, data.current_meeting);
                    updateScheduleList(data.upcoming_meetings || [], data.current_meeting);
                    
                    if (currentMeetingData) {
                        startCountdown();
                    } else {
                        stopCountdown();
                    }
                    
                    hideLoading();
                }
            } catch (error) {
                console.error('Error fetching meeting status:', error);
            }
        }


        function updateMeetingCardSmooth(status, currentMeeting) {
            const contentElement = document.getElementById('meetingCardContent');
            
            const newContent = generateMeetingContent(status, currentMeeting);
            if (contentElement.innerHTML === newContent) {
                return;
            }
            
            contentElement.style.opacity = '0.5';
            
            setTimeout(() => {
                contentElement.innerHTML = newContent;
                contentElement.style.opacity = '1';
            }, 150);
        }


        function generateMeetingContent(status, currentMeeting) {
            if (status === 'ongoing' && currentMeeting) {
                return generateOngoingMeetingHTML(currentMeeting);
            } else if (status === 'no_meeting') {
                return generateNoMeetingHTML();
            } else {
                return generateAvailableHTML();
            }
        }


        function generateOngoingMeetingHTML(meeting) {
            const meetingType = getMeetingTypeBadge(meeting.meeting_type);
            
            return `
                <div class="card-title ongoing">🟢 THIS MEETING</div>
                <div class="meeting-topic">${escapeHtml(meeting.topic)}</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Time</div>
                        <div class="info-value">${meeting.start_time} - ${meeting.end_time}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Meeting Type</div>
                        <div class="info-value">${meetingType}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Requested By</div>
                        <div class="info-value">${escapeHtml(meeting.requester_name)}</div>
                    </div>
                    ${meeting.requester_position ? `
                    <div class="info-item">
                        <div class="info-label">Position</div>
                        <div class="info-value">${escapeHtml(meeting.requester_position)}</div>
                    </div>
                    ` : ''}
                    ${meeting.platform_name ? `
                    <div class="info-item">
                        <div class="info-label">Platform</div>
                        <div class="info-value">${escapeHtml(meeting.platform_name)}</div>
                    </div>
                    ` : ''}
                </div>
            `;
        }


        function generateNoMeetingHTML() {
            return `
                <div class="no-meeting-container">
                    <div class="no-meeting-icon">‎</div>
                    <div class="no-meeting-subtitle">No Meeting Now</div>
                </div>
            `;
        }


        function generateAvailableHTML() {
            return `
                <div class="no-meeting-container">
                    <div class="no-meeting-icon">‎ </div>
                    <div class="no-meeting-subtitle">No Meeting Now</div>
                </div>
            `;
        }

        function updateScheduleList(meetings, currentMeeting) {
            const scheduleList = document.getElementById('scheduleList');
            
            if (meetings.length === 0 && !currentMeeting) {
                scheduleList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-title">No schedule</div>
                        <div class="empty-subtitle">No meetings scheduled</div>
                    </div>
                `;
                return;
            }
            
            let allMeetings = [];
            if (currentMeeting) {
                allMeetings.push({...currentMeeting, isActive: true});
            }
            allMeetings = allMeetings.concat(meetings.map(m => ({...m, isActive: false})));
            
            allMeetings = allMeetings.slice(0, 5);
            
            scheduleList.innerHTML = allMeetings.map(meeting => {
                const activeClass = meeting.isActive ? 'active' : '';
                
                let progressHTML = '';
                if (meeting.isActive) {
                    const progress = calculateProgress(meeting.start_time_full, meeting.end_time_full);
                    const timeRemaining = calculateTimeRemainingDetailed(meeting.end_time_full);
                    progressHTML = `
                        <div class="progress-wrapper">
                            <div class="progress-header">
                                <span class="progress-label">Progress</span>
                                <span class="progress-time">${timeRemaining}</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: ${progress}%"></div>
                            </div>
                        </div>
                    `;
                }
                
                return `
                    <div class="schedule-item ${activeClass}">
                        <div class="schedule-time">${meeting.start_time} - ${meeting.end_time}</div>
                        <div class="schedule-topic">${escapeHtml(meeting.topic)}</div>
                        <div class="schedule-organizer">${escapeHtml(meeting.requester_name)}</div>
                        ${progressHTML}
                    </div>
                `;
            }).join('');
        }


        function getMeetingTypeBadge(type) {
            const badges = {
                'online': '<span class="meeting-type-badge badge-online">Online</span>',
                'offline': '<span class="meeting-type-badge badge-offline">Offline</span>',
                'hybrid': '<span class="meeting-type-badge badge-hybrid">Hybrid</span>'
            };
            return badges[type] || type;
        }


        function calculateProgress(startTime, endTime) {
            const now = new Date();
            const start = parseTime(startTime);
            const end = parseTime(endTime);
            
            if (now < start) return 0;
            if (now > end) return 100;
            
            const total = end - start;
            const elapsed = now - start;
            return Math.round((elapsed / total) * 100);
        }


        function calculateTimeRemainingDetailed(endTime) {
            const now = new Date();
            const end = parseTime(endTime);
            const diff = end - now;
            
            if (diff <= 0) return 'Ending soon';
            
            const hours = Math.floor(diff / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            
            if (hours > 0) {
                return `${hours}h ${minutes}min ${seconds}sec`;
            } else if (minutes > 0) {
                return `${minutes}min ${seconds}sec`;
            } else {
                return `${seconds}sec`;
            }
        }


        function parseTime(timeString) {
            const [hours, minutes, seconds] = timeString.split(':');
            const date = new Date();
            date.setHours(parseInt(hours), parseInt(minutes), parseInt(seconds || 0), 0);
            return date;
        }


        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }


        function hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.classList.add('hide');
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 300);
        }

        // ===== INITIALIZATION =====
        
        updateDateTime();
        setInterval(updateDateTime, CLOCK_INTERVAL);
        
        fetchMeetingStatus();
        
        setInterval(fetchMeetingStatus, REFRESH_INTERVAL);
        
        console.log('Meeting Display System v2.0 Initialized');
        console.log('Room ID:', ROOM_ID);
        console.log('Refresh Interval:', REFRESH_INTERVAL / 1000, 'seconds');
    </script>
</body>
</html>