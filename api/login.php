<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    setcookie('remember_user', '', time() - 3600, "/");
    header('Location: api/login.php');
    exit;
}

require '../api/db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header('Location: api/dashboard.php');
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/loginStyleSheet.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="login-box">
        <div class="text-center">
            <img src="assets/TM_logo.svg" alt="TMS Logo" width="135">
            <h2 class="login-title">Log in to your account</h2>
        </div>
        
        <?php if ($error): ?>
            <p style="color:#ff6b6b; text-align:center; font-size:14px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="api/login.php" method="POST">
            <input type="email" name="email" class="form-control custom-input top-input" placeholder="Email address" required>
            <input type="password" name="password" class="form-control custom-input bottom-input" placeholder="Password" required>

            <div class="d-flex justify-content-between align-items-center my-3">
                <div class="form-check">
                    <input class="form-check-input custom-checkbox" type="checkbox" id="remember">
                    <label class="form-check-label remember-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="link-purple">Forgot password?</a>
            </div>


            <button type="submit" class="btn w-100 btn-login">Log in</button>
        </form>

        <p class="text-center bottom-text">
            Not a member? <a href="api/signup.php" class="link-purple">Sign up</a>
        </p>
    </div>
</body>
</html>