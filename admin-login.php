<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/functions.php'; // Include database connection functions
require_once 'includes/db.php'; // Include database connection

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve username and password from POST request
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);

    // Prepare and execute the SQL query to fetch the admin record
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if an admin record was found
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Verify the password
        //  prisnt admin and password  
        if (password_verify($password, $admin['password'])) {
            // Password matches, start the session and redirect to the admin dashboard
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password.";
        }
    } else {
        // No admin record found
        $error_message = "Invalid username or password.";
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">

    <title>Admin Login - Academic Resource Portal</title>
</head>

<body>
    <div class="form-container">

        <?php if (!empty($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        } ?>
        <form action="admin-login.php" method="POST">
            <h2>Admin Login</h2>
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" name="username" id="username" placeholder="Username or Email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="button">Sign In</button>
            <hr class="dashed">
            <a href="signin.php" class="button-admin">Sign in as User</a>
        </form>
        <a href="index.php" class="button-back">Back to Home</a>
    </div>
</body>

</html>