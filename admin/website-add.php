<?php
require_once 'header.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) die("CSRF mismatch");
    $name = sanitize($_POST['name']);
    $url = sanitize($_POST['url']);
    $interval = (int)$_POST['interval'];
    $threshold = (int)$_POST['threshold'];

    if ($name && $url) {
        $stmt = $pdo->prepare("INSERT INTO websites (name, url, monitoring_interval, slow_threshold) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $url, $interval, $threshold]);
        header("Location: websites.php");
        exit;
    } else { $error = "All fields are required."; }
}
$csrf_token = generate_csrf_token();
?>
<h2>Add Website</h2>
<?php if($error): ?><div style="color:var(--danger); margin-bottom:15px;"><?= $error ?></div><?php endif; ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="form-group"><label>Website Name</label><input type="text" name="name" required></div>
    <div class="form-group"><label>Website URL</label><input type="url" name="url" placeholder="https://example.com" required></div>
    <div class="form-group"><label>Interval (Minutes)</label><input type="number" name="interval" value="5" min="1" required></div>
    <div class="form-group"><label>Slow Threshold (ms)</label><input type="number" name="threshold" value="3000" min="100" required></div>
    <button type="submit" class="btn">Save Website</button>
</form>
<?php require_once 'footer.php'; ?>