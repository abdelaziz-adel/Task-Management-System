<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboardStyleSheet.css">
    <link rel="stylesheet" href="css/profileStyleSheet.css">
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

    <div class="profile-form">

        <div class="profile-field">
            <label>Name</label>
            <input type="text" class="custom-input" value="">
        </div>

        <div class="profile-field">
            <label>Email</label>
            <input type="email" class="custom-input" value="">
        </div>

        <div class="profile-field">
            <label>Password</label>

            <input
                type="password"
                class="custom-input"
                placeholder="Enter your current password"
            >

            <input
                type="password"
                class="custom-input new-password"
                placeholder="Enter new password"
            >
        </div>

    </div>

</div>

</body>
</html>