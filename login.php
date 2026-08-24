<?php
require_once 'includes/database.php';
require_once 'includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="justify-content:center; align-items:center;">
    <form method="POST" style="width: 100%; max-width: 400px;">
        <h2 style="margin-bottom: 20px;">Admin Login</h2>
        <?php if($error): ?><div style="color:var(--danger); margin-bottom:15px;"><?= $error ?></div><?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div style="position:relative;">
                <input type="password" name="password" id="password" required style="padding-right: 50px;">
                <button type="button" id="togglePassword" style="position:absolute; right:10px; top:10px; background:none; border:none; color:var(--primary); cursor:pointer;">Show</button>
            </div>
        </div>
        <button type="submit" class="btn" style="width:100%;">Login</button>
    </form>
    <script src="assets/js/script.js"></script>
</body>
</html>
