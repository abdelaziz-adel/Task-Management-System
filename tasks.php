<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$current_user_id = $_SESSION['user_id'];

require 'db.php';

// Optional filters: status or "my tasks only"
$status_filter = $_GET['status'] ?? '';
$my_tasks_only = isset($_GET['mine']);

$sql = "SELECT t.*, u.name AS assigned_name
        FROM tasks t
        LEFT JOIN users u ON t.assigned_to = u.id
        WHERE 1=1";
$params = [];

if ($status_filter !== '' && in_array($status_filter, ['Pending', 'In Progress', 'Done'])) {
    $sql .= " AND t.status = ?";
    $params[] = $status_filter;
}
if ($my_tasks_only) {
    $sql .= " AND t.assigned_to = ?";
    $params[] = $current_user_id;
}

$sql .= " ORDER BY t.due_date IS NULL, t.due_date ASC, t.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();

function statusBadgeClass($status) {
    return match ($status) {
        'Pending' => 'bg-warning text-dark',
        'In Progress' => 'bg-info text-dark',
        'Done' => 'bg-success',
        default => 'bg-secondary',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboardStyleSheet.css">
    <link rel="stylesheet" href="css/tasksStyleSheet.css">
</head>

<body>

<nav class="navbar">

        <a href="tasks.php" class="logo">
            <img src="assets/TM_logo.svg" alt="Logo">
        </a>

        <div class="nav-links">
            <a href="tasks.php" style="color: #2DD4BF">Tasks</a>
            <a href="profile.php">Profile</a>
        </div>

    </nav>

    <div class="tasks-container">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h1 class="h3 mb-0">Tasks</h1>
            <a href="tasks/add_task.php" class="btn-primary">Add Task <span id="plus-sign">＋</span></a>
        </div>

        <div class="btn-group mb-3" role="group">
            <a href="tasks.php" class="btn btn-outline-secondary <?= ($status_filter === '' && !$my_tasks_only) ? 'active' : '' ?>">All</a>
            <a href="?status=Pending" class="btn btn-outline-secondary <?= $status_filter === 'Pending' ? 'active' : '' ?>">Pending</a>
            <a href="?status=In+Progress" class="btn btn-outline-secondary <?= $status_filter === 'In Progress' ? 'active' : '' ?>">In Progress</a>
            <a href="?status=Done" class="btn btn-outline-secondary <?= $status_filter === 'Done' ? 'active' : '' ?>">Done</a>
            <a href="?mine=1" class="btn btn-outline-secondary <?= $my_tasks_only ? 'active' : '' ?>">My Tasks</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No tasks yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($tasks as $t): ?>
                        <tr>
                            <td><?= (int)$t['id'] ?></td>
                            <td><?= htmlspecialchars($t['title']) ?></td>
                            <td><?= htmlspecialchars($t['description'] ?? '') ?></td>
                            <td><span class="badge <?= statusBadgeClass($t['status']) ?>"><?= htmlspecialchars($t['status']) ?></span></td>
                            <td><?= $t['assigned_name'] ? htmlspecialchars($t['assigned_name']) : '—' ?></td>
                            <td><?= $t['due_date'] ? htmlspecialchars($t['due_date']) : '—' ?></td>
                            <td>
                                <a href="tasks/edit_task.php?id=<?= (int)$t['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="tasks/delete_task.php?id=<?= (int)$t['id'] ?>" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
