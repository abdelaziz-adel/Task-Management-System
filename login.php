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

        <form action="login.php" method="POST">
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
            Not a member? <a href="signup.php" class="link-purple">Sign up</a>
        </p>
    </div>

    <script src="js/loginScript.js"></script>
</body>
</html>