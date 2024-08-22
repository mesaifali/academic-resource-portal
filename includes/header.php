<?php
session_start(); // Start the session at the beginning of the file

// Function to check if a user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if an admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Academic Resource Portal</title>
</head>
<body>
    <header>
        <nav class="navbar">
                        <a href="index.php" class="navbar-brand">Academic Resource Portal</a>
            <div class="container">
                <ul class="navbar-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="resources.php">Resources</a></li>
                    <?php if (isUserLoggedIn() || isAdminLoggedIn()): ?>
                        <li><a href="<?php echo isAdminLoggedIn() ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>">Dashboard</a></li>
                        <li><a href="user/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a class="signin-button" href="signin.php">Sign In</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

