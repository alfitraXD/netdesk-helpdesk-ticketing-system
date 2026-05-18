<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('DELETE FROM devices WHERE id = ?');
    $stmt->execute([$id]);
    flash('success', 'Device deleted successfully.');
}

redirect('index.php');
