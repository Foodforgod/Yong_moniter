<?php
require_once 'header.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM websites WHERE id = ?");
$stmt->execute([$id]);
$website = $stmt->fetch();
if (!$website) { header("Location: websites.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) die("CSRF mismatch");
    $name = sanitize($_POST['name']);
    $url = sanitize($_POST['url']);
    $interval = (int)$_POST['interval'];
    $threshold = (int)$_POST['threshold'];

    $stmt = $pdo->prepare("UPDATE websites SET name=?, url=?, monitoring_interval=?, slow_threshold=? WHERE id=?");
    $stmt->execute([$name, $url, $interval, $threshold, $id]);
    header("Location: websites.php");
    exit;
}
$csrf_token = generate_csrf_token();
?>
<h2>Edit Website</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="form-group"><label>Website Name</label><input type="text" name="name" value="<?= sanitize($website['name']) ?>" required></div>
    <div class="form-group"><label>Website URL</label><input type="url" name="url" value="<?= sanitize($website['url']) ?>" required></div>
    <div class="form-group"><label>Interval (Minutes)</label><input type="number" name="interval" value="<?= $website['monitoring_interval'] ?>" required></div>
    <div class="form-group"><label>Slow Threshold (ms)</label><input type="number" name="threshold" value="<?= $website['slow_threshold'] ?>" required></div>
    <button type="submit" class="btn">Update Website</button>
</form>
<?php require_once 'footer.php'; ?>