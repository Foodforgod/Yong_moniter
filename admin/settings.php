<?php
require_once 'header.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) die("CSRF mismatch");
    $token = sanitize($_POST['bot_token']);
    $chat_id = sanitize($_POST['chat_id']);
    $enabled = isset($_POST['enabled']) ? 1 : 0;

    if (isset($_POST['test_telegram'])) {
        require_once __DIR__ . '/../includes/telegram.php';
        $res = send_telegram_alert($token, $chat_id, "🔔 <b>Test Telegram Alert</b>\nWebsite Monitor system connected successfully!");
        $msg = $res ? "Test message sent!" : "Failed to send test message.";
    } else {
        $stmt = $pdo->prepare("UPDATE telegram_config SET bot_token=?, chat_id=?, enabled=? WHERE id=1");
        $stmt->execute([$token, $chat_id, $enabled]);
        $msg = "Settings updated successfully!";
    }
}
$config = $pdo->query("SELECT * FROM telegram_config WHERE id=1")->fetch();
$csrf_token = generate_csrf_token();
?>
<h2>Telegram Settings</h2>
<?php if($msg): ?><div style="padding:10px; background:#d1fae5; color:#065f46; margin-bottom:15px; border-radius:6px;"><?= $msg ?></div><?php endif; ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <div class="form-group"><label>Bot Token</label><input type="text" name="bot_token" value="<?= sanitize($config['bot_token']) ?>"></div>
    <div class="form-group"><label>Chat ID</label><input type="text" name="chat_id" value="<?= sanitize($config['chat_id']) ?>"></div>
    <div class="form-group"><label><input type="checkbox" name="enabled" value="1" <?= $config['enabled'] ? 'checked' : '' ?>> Enable Telegram Alerts</label></div>
    <button type="submit" class="btn">Save Configuration</button>
    <button type="submit" name="test_telegram" value="1" class="btn btn-secondary">Test Telegram</button>
</form>
<?php require_once 'footer.php'; ?>