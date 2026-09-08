<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

$task_id = (int)($_GET['id'] ?? 0);

if ($task_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
}

header('Location: tasks.php');
exit;
