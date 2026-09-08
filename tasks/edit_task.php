<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
$current_user_id = $_SESSION['user_id'];

require '../db.php';

$task_id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$task_id]);
$task = $stmt->fetch();

if (!$task) {
    header('Location: ../tasks.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Pending';
    $assigned_to = $_POST['assigned_to'] !== '' ? (int)$_POST['assigned_to'] : null;
    $due_date = $_POST['due_date'] !== '' ? $_POST['due_date'] : null;

    if ($title === '') {
        $errors[] = 'Please enter a task title.';
    }
    if (!in_array($status, ['Pending', 'In Progress', 'Done'])) {
        $errors[] = 'Invalid status.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "UPDATE tasks SET title = ?, description = ?, status = ?, assigned_to = ?, due_date = ?
             WHERE id = ?"
        );
        $stmt->execute([$title, $description, $status, $assigned_to, $due_date, $task_id]);

        header('Location: ../tasks.php');
        exit;
    }
    $task = array_merge($task, $_POST);
}

$stmt = $pdo->prepare("SELECT id, name FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Task</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/dashboardStyleSheet.css">
<link rel="stylesheet" href="../css/addTaskStyleSheet.css">
</head>
<body>

<nav class="navbar">
    <a href="../dashboard.php" class="logo">
        <img src="../assets/TM_logo.svg" alt="Logo">
    </a>
    <div class="nav-links">
        <a href="../tasks.php" style="color: #2DD4BF">Tasks</a>
        <a href="../profile.php">Profile</a>
    </div>
</nav>

<div class="container mt-4" style="max-width: 550px;">
    <h1 class="h3 mb-3">Edit Task #<?= (int)$task['id'] ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Task Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <?php foreach (['Pending', 'In Progress', 'Done'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($task['status'] === $s) ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Assigned To</label>
            <select name="assigned_to" class="form-select">
                <option value="">— Unassigned —</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($task['assigned_to'] == $u['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($task['due_date'] ?? '') ?>">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="../tasks.php" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
