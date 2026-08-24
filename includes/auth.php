<?php
require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: /website-monitor/login.php");
    exit;
}
?>