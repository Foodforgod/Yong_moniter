<?php
require_once __DIR__ . '/../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand">Website Monitor</div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="websites.php">Websites</a>
            <a href="logs.php">Monitoring Logs</a>
            <a href="incidents.php">Incidents</a>
            <a href="settings.php">Settings</a>
            <a href="../logout.php" style="color:var(--danger);">Logout</a>
        </nav>
    </div>
    <div class="main-content">
        <div class="topbar">
            <span>Welcome, <strong><?= sanitize($_SESSION['admin_username']) ?></strong></span>
            <span><?= date('d M Y, h:i A') ?></span>
        </div>
        <div class="content">