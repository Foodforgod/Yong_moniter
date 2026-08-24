<?php
require_once 'header.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM websites WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: websites.php");
    exit;
}

$websites = $pdo->query("SELECT * FROM websites ORDER BY id DESC")->fetchAll();
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Website Management</h2>
    <a href="website-add.php" class="btn">Add Website</a>
</div>
<table>
    <tr><th>Name</th><th>URL</th><th>Interval</th><th>Threshold</th><th>Status</th><th>Actions</th></tr>
    <?php foreach($websites as $w): ?>
    <tr>
        <td><?= sanitize($w['name']) ?></td>
        <td><a href="<?= sanitize($w['url']) ?>" target="_blank"><?= sanitize($w['url']) ?></a></td>
        <td><?= $w['monitoring_interval'] ?>m</td>
        <td><?= $w['slow_threshold'] ?>ms</td>
        <td><span class="badge badge-<?= strtolower($w['current_status']) ?>"><?= $w['current_status'] ?></span></td>
        <td>
            <a href="website-edit.php?id=<?= $w['id'] ?>" class="btn" style="padding:4px 8px; font-size:0.8rem;">Edit</a>
            <a href="websites.php?delete=<?= $w['id'] ?>" class="btn btn-danger" style="padding:4px 8px; font-size:0.8rem;" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require_once 'footer.php'; ?>