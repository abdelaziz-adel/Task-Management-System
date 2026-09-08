<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

function respond($success, $extra = []) {
    echo json_encode(array_merge(['success' => $success], $extra));
    exit;
}

if (!isset($_SESSION['user_id'])) {
    respond(false, ['message' => 'Not authenticated']);
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$action = $input['action'] ?? '';
$field = $input['field'] ?? '';
$value = trim($input['value'] ?? '');
$current_password = $input['current_password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !password_verify($current_password, $user['password'])) {
    respond(false, ['message' => 'Incorrect password']);
}
if (!in_array($field, ['name', 'email', 'password'])) {
    respond(false, ['message' => 'Invalid field']);
}

if ($action === 'verify') {
    $realValue = ($field === 'password') ? '' : $user[$field];
    respond(true, ['value' => $realValue]);
}

if ($action === 'update') {
    if ($field === 'name') {
        if ($value === '') respond(false, ['message' => 'Name cannot be empty']);
        $pdo->prepare("UPDATE users SET name = ? WHERE id = ?")->execute([$value, $user_id]);
        $_SESSION['user_name'] = $value;
        respond(true, ['value' => $value]);
    }

    if ($field === 'email') {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$value, $user_id]);
        if ($check->fetch()) respond(false, ['message' => 'This email is already in use']);

        $pdo->prepare("UPDATE users SET email = ? WHERE id = ?")->execute([$value, $user_id]);
        respond(true, ['value' => maskEmail($value)]);
    }

    if ($field === 'password') {
        if (strlen($value) < 6) respond(false, ['message' => 'Password must be at least 6 characters']);

        $hashed = password_hash($value, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hashed, $user_id]);
        respond(true);
    }
}

respond(false, ['message' => 'Invalid action']);