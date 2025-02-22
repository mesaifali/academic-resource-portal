<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/functions.php'; // Include database connection functions
require_once 'includes/db.php'; 

$error_message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username or Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    if (empty($errors)) {
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
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        } else {
            // No admin record found
            $errors[] = "Invalid username or password";
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Admin Login - Academic Resource Portal</title>
    <style>
        .error-message {
            color: #e53e3e;
            background-color: #fff5f5;
            border: 1px solid #fc8181;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .form-group {
            position: relative;
        }
        .form-group i {
            position: absolute;
            right: 10px;
            top: 40px;
            color: #718096;
        }
    </style>
</head>

<body>
    <div class="form-container">

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 10px;">
                    <?php foreach ($errors as $error): ?>
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="admin-login.php" method="POST">
            <h2><i class="fas fa-user-shield"></i> Admin Login</h2>
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Username or Email:</label>
                <input type="text" name="username" id="username" placeholder="Enter your username or email" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="button">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
            <hr class="dashed">
            <a href="signin.php" class="button-admin">
                <i class="fas fa-user"></i> Sign in as User
            </a>
        </form>
        <a href="index.php" class="button-back">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>
</body>

</html>