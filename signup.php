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

        <form action="signup.php" method="POST">
            <input type="name" name="name" class="form-control custom-input top-input" placeholder="Name" required>
            <input type="email" name="email" class="form-control custom-input middle-input" placeholder="Email address" required>
            <input type="password" name="password" class="form-control custom-input bottom-input" placeholder="Password" required>

            <br>
            <!-- default is to be remembered -->

            <button type="submit" class="btn w-100 btn-login">Sign up</button>
        </form>

        <p class="text-center bottom-text">
            Already have an account? <a href="login.php" class="link-purple">Log in</a>
        </p>
    </div>

    <script src="js/signupScript.js"></script>
</body>
</html>