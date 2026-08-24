<?php
require_once __DIR__ . '/../includes/database.php';

$pdo->query("DELETE FROM monitoring_logs WHERE checked_at < DATE_SUB(NOW(), INTERVAL 90 DAY)");
echo "Cleanup completed successfully.";