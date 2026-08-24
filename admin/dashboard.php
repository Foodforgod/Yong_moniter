<?php
require_once 'header.php';

$total_sites = $pdo->query("SELECT COUNT(*) FROM websites")->fetchColumn();
$up_sites = $pdo->query("SELECT COUNT(*) FROM websites WHERE current_status = 'UP'")->fetchColumn();
$down_sites = $pdo->query("SELECT COUNT(*) FROM websites WHERE current_status = 'DOWN'")->fetchColumn();
$slow_sites = $pdo->query("SELECT COUNT(*) FROM websites WHERE current_status = 'SLOW'")->fetchColumn();

$logs = $pdo->query("SELECT l.*, w.name FROM monitoring_logs l JOIN websites w ON l.website_id = w.id ORDER BY l.checked_at DESC LIMIT 5")->fetchAll();
$incidents = $pdo->query("SELECT i.*, w.name FROM incidents i JOIN websites w ON i.website_id = w.id ORDER BY i.created_at DESC LIMIT 5")->fetchAll();
?>

<h2>Dashboard Overview</h2>
<div class="stats-grid" style="margin-top: 20px;">
    <div class="stat-card"><h3>Total Websites</h3><div class="value"><?= $total_sites ?></div></div>
    <div class="stat-card"><h3>UP Websites</h3><div class="value" style="color:var(--success);"><?= $up_sites ?></div></div>
    <div class="stat-card"><h3>DOWN Websites</h3><div class="value" style="color:var(--danger);"><?= $down_sites ?></div></div>
    <div class="stat-card"><h3>SLOW Websites</h3><div class="value" style="color:var(--warning);"><?= $slow_sites ?></div></div>
</div>

<h3>Recent Monitoring Activity</h3>
<table>
    <tr><th>Website</th><th>Status</th><th>Response Time</th><th>Checked At</th></tr>
    <?php foreach($logs as $l): ?>
    <tr>
        <td><?= sanitize($l['name']) ?></td>
        <td><span class="badge badge-<?= strtolower($l['status']) ?>"><?= $l['status'] ?></span></td>
        <td><?= $l['response_time'] ?> ms</td>
        <td><?= $l['checked_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once 'footer.php'; ?>