<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 40px;
}

.container {
    max-width: 1400px;
    margin: auto;
}

.header {
    text-align: center;
    margin-bottom: 50px;
}

.company-name {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
}

.subtitle {
    color: #64748b;
    margin-top: 6px;
}

h1 {
    margin-top: 20px;
    font-size: 42px;
    font-weight: 800;
    color: #0f172a;
}

.rooms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.room-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px;
    text-decoration: none;
    color: #0f172a;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
    border-top: 6px solid #2563eb;
}

.room-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.room-name {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 12px;
}

.room-capacity {
    font-size: 15px;
    color: #475569;
    margin-bottom: 18px;
}

.room-status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-inactive {
    background: #fee2e2;
    color: #991b1b;
}

.footer {
    text-align: center;
    margin-top: 60px;
    color: #64748b;
    font-size: 14px;
}
</style>
</head>
<body>
<div class="container">

<div class="header">
    <div class="company-name">PT TJB POWER SERVICES</div>
    <div class="subtitle">Meeting Room Display System</div>
    <h1>Select Meeting Room</h1>
</div>

<?php if(empty($rooms)): ?>
    <p style="text-align:center;color:#64748b;font-size:20px;">No active rooms available</p>
<?php else: ?>
<div class="rooms-grid">
<?php foreach($rooms as $room): ?>
    <a href="<?= base_url('display/room/'.$room['id']) ?>" class="room-card" target="_blank">
        <div class="room-name"><?= htmlspecialchars($room['room_name']) ?></div>
        <div class="room-capacity">Capacity: <?= $room['capacity'] ?> persons</div>
        <span class="room-status status-<?= $room['status'] ?>">
            <?= strtoupper($room['status']) ?>
        </span>
    </a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="footer">
    © <?= date('Y') ?> PT TJB Power Services
</div>

</div>
</body>
</html>
