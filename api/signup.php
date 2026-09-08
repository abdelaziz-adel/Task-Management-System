<?php
require 'api/db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    //email already used?
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        $error = "This email is already registered";
    } else if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        header('Location: api/login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/signupStyleSheet.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="login-box">
        <div class="text-center">
            <img src="assets/TM_logo.svg" alt="TMS Logo" width="135">
            <h2 class="login-title">Sign up to Task Manager!</h2>
        </div>

        <?php if ($error): ?>
            <p style="color:#ff6b6b; text-align:center; font-size:14px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="api/signup.php" method="POST">
            <input type="text" name="name" class="form-control custom-input top-input" placeholder="Name" required>
            <input type="email" name="email" class="form-control custom-input middle-input" placeholder="Email address" required>
            <input type="password" name="password" class="form-control custom-input bottom-input" placeholder="Password" required>

            <br>
            <!-- default is to be remembered -->

            <button type="submit" class="btn w-100 btn-login">Sign up</button>
        </form>

        <p class="text-center bottom-text">
            Already have an account? <a href="api/login.php" class="link-purple">Log in</a>
        </p>
    </div>
</body>
</html>