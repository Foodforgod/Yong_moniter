<?php
require_once 'header.php';
$incidents = $pdo->query("SELECT i.*, w.name FROM incidents i JOIN websites w ON i.website_id = w.id ORDER BY i.created_at DESC")->fetchAll();
?>
<h2>Incident History</h2>
<table>
    <tr><th>Website</th><th>Previous</th><th>Current</th><th>Started At</th><th>Resolved At</th></tr>
    <?php foreach($incidents as $inc): ?>
    <tr>
        <td><?= sanitize($inc['name']) ?></td>
        <td><?= $inc['previous_status'] ?></td>
        <td><?= $inc['current_status'] ?></td>
        <td><?= $inc['created_at'] ?></td>
        <td><?= $inc['resolved_at'] ?? 'Ongoing' ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php require_once 'footer.php'; ?>