<?php
require_once 'includes/database.php';
require_once 'includes/functions.php';

$site_id = $_GET['site'] ?? null;

if ($site_id) {
    // Single Website Details
    $stmt = $pdo->prepare("SELECT * FROM websites WHERE id = ?");
    $stmt->execute([$site_id]);
    $site = $stmt->fetch();
    if (!$site) { header("Location: status.php"); exit; }

    // 90 Day Logs
    $stmt = $pdo->prepare("SELECT * FROM monitoring_logs WHERE website_id = ? AND checked_at >= DATE_SUB(NOW(), INTERVAL 90 DAY) ORDER BY checked_at DESC");
    $stmt->execute([$site_id]);
    $logs = $stmt->fetchAll();

    // Incidents
    $stmt = $pdo->prepare("SELECT * FROM incidents WHERE website_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$site_id]);
    $incidents = $stmt->fetchAll();
} else {
    // All Websites Overview
    $websites = $pdo->query("SELECT * FROM websites WHERE enabled = 1")->fetchAll();
    
    // Check Overall System Status
    $overall_status = 'UP';
    foreach ($websites as $w) {
        if ($w['current_status'] === 'DOWN') { $overall_status = 'DOWN'; break; }
        if ($w['current_status'] === 'SLOW' && $overall_status !== 'DOWN') { $overall_status = 'SLOW'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Public Status Page</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="flex-direction: column; align-items: center;">
    <div class="container">
        <div class="public-header">
            <h1>System Status Page</h1>
            <p>Real-time availability and monitoring performance</p>
        </div>

        <?php if ($site_id): ?>
            <p><a href="status.php" class="btn btn-secondary" style="margin-bottom:20px;">&larr; Back to All Systems</a></p>
            <div class="stat-card" style="margin-bottom:20px;">
                <h2><?= sanitize($site['name']) ?></h2>
                <p><a href="<?= sanitize($site['url']) ?>" target="_blank"><?= sanitize($site['url']) ?></a></p>
                <div style="margin-top: 15px;">
                    <span class="badge badge-<?= strtolower($site['current_status']) ?>"><?= $site['current_status'] ?></span>
                    <span>Response: <strong><?= $site['response_time'] ?> ms</strong></span>
                    <span>Last Checked: <strong><?= $site['last_checked'] ?? 'Never' ?></strong></span>
                </div>
            </div>

            <h3>Recent Incidents</h3>
            <?php if (empty($incidents)): ?>
                <p style="padding:15px; background:#fff; border-radius:6px; border:1px solid var(--border-color); margin-bottom:20px;">No recent incidents recorded.</p>
            <?php else: ?>
                <table>
                    <tr><th>Previous</th><th>Current</th><th>Started</th><th>Resolved</th></tr>
                    <?php foreach($incidents as $inc): ?>
                        <tr>
                            <td><?= $inc['previous_status'] ?></td>
                            <td><?= $inc['current_status'] ?></td>
                            <td><?= $inc['created_at'] ?></td>
                            <td><?= $inc['resolved_at'] ?? 'Ongoing' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

        <?php else: ?>
            <div class="system-status-banner banner-<?= strtolower($overall_status) ?>">
                <?php if ($overall_status === 'UP'): ?>🟢 All Systems Operational
                <?php elseif ($overall_status === 'SLOW'): ?>⚠️ Some Systems Experiencing Slowness
                <?php else: ?>🔴 System Disruption Detected<?php endif; ?>
            </div>

            <h2>Monitored Services</h2>
            <div style="margin-top: 15px;">
                <?php foreach($websites as $w): ?>
                    <a href="status.php?site=<?= $w['id'] ?>" class="status-item">
                        <div>
                            <strong><?= sanitize($w['name']) ?></strong>
                            <div style="font-size:0.8rem; color:#64748b;"><?= sanitize($w['url']) ?></div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge badge-<?= strtolower($w['current_status']) ?>"><?= $w['current_status'] ?></span>
                            <div style="font-size:0.8rem; color:#64748b; margin-top:4px;"><?= $w['response_time'] ?> ms</div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>