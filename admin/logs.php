<?php
require_once 'header.php';
$filter = $_GET['filter'] ?? 'all';
$query = "SELECT l.*, w.name FROM monitoring_logs l JOIN websites w ON l.website_id = w.id";
if ($filter === 'UP') $query .= " WHERE l.status = 'UP'";
elseif ($filter === 'DOWN') $query .= " WHERE l.status = 'DOWN'";
elseif ($filter === 'SLOW') $query .= " WHERE l.status = 'SLOW'";
elseif ($filter === 'today') $query .= " WHERE DATE(l.checked_at) = CURDATE()";

$query .= " ORDER BY l.checked_at DESC LIMIT 50";
$logs = $pdo->query($query)->fetchAll();
?>
<h2>Monitoring Logs</h2>
<div style="margin: 15px 0;">
    <a href="logs.php?filter=all" class="btn btn-secondary">All</a>
    <a href="logs.php?filter=UP" class="btn">UP</a>
    <a href="logs.php?filter=DOWN" class="btn btn-danger">DOWN</a>
    <a href="logs.php?filter=SLOW" class="btn" style="background:var(--warning);">SLOW</a>
    <a href="logs.php?filter=today" class="btn btn-secondary">Today</a>
</div>
<table>
    <tr><th>Website</th><th>Status</th><th>Response Time</th><th>HTTP Code</th><th>Error</th><th>Checked At</th></tr>
    <?php foreach($logs as $l): ?>
    <tr>
        <td><?= sanitize($l['name']) ?></td>
        <td><span class="badge badge-<?= strtolower($l['status']) ?>"><?= $l['status'] ?></span></td>
        <td><?= $l['response_time'] ?> ms</td>
        <td><?= $l['http_status_code'] ?? '-' ?></td>
        <td><?= sanitize($l['error_message'] ?? '-') ?></td>
        <td><?= $l['checked_at'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require_once 'footer.php'; ?>