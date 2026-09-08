<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$maskedEmail = maskEmail($user['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/dashboardStyleSheet.css">
    <link rel="stylesheet" href="/css/profileStyleSheet.css?v=3">
</head>

<body>

    <nav class="navbar">

        <a href="dashboard.php" class="logo">
            <img src="assets/TM_logo.svg" alt="Logo">
        </a>

        <div class="nav-links">
            <a href="tasks.php">Tasks</a>
            <a href="profile.php" style="color: #2DD4BF">Profile</a>
        </div>

    </nav>

    <div class="profile-container">

        <h2 class="profile-title">Profile</h2>

        <p id="pageMessage" class="page-message"></p>

        <div class="profile-form">

            <div class="profile-field">
                <label>Name</label>
                <div class="field-with-icon">
                    <input type="text" id="field-name" class="custom-input" value="<?= htmlspecialchars($user['name']) ?>" readonly>
                    <button type="button" class="edit-icon-btn" data-field="name">&#9998;</button>
                    <button type="button" class="save-icon-btn" data-field="name" style="display:none;">&#10003;</button>
                </div>
            </div>

            <div class="profile-field">
                <label>Email</label>
                <div class="field-with-icon">
                    <input type="text" id="field-email" class="custom-input" value="<?= htmlspecialchars($maskedEmail) ?>" readonly>
                    <button type="button" class="edit-icon-btn" data-field="email">&#9998;</button>
                    <button type="button" class="save-icon-btn" data-field="email" style="display:none;">&#10003;</button>
                </div>
            </div>

            <div class="profile-field">
                <label>Password</label>
                <div class="field-with-icon">
                    <input type="text" id="field-password" class="custom-input" value="**********" readonly>
                    <button type="button" class="edit-icon-btn" data-field="password">&#9998;</button>
                    <button type="button" class="save-icon-btn" data-field="password" style="display:none;">&#10003;</button>
                </div>
            </div>

            <a href="login.php?logout=1" class="btn btn-logout w-100">Logout</a>

        </div>

    </div>

    <!-- password popup -->
    <div class="modal fade" id="confirmPasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content confirm-modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Confirm Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div style="padding: 0 16px;">
                    <p id="modalErrorMsg" class="modal-error"></p>
                    <input type="password" id="modalPasswordInput" class="custom-input" placeholder="Enter your current password">
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-login w-100" id="modalConfirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/profileScript.js"></script>

</body>
</html>