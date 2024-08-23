<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: user/dashboard.php");
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Sign In - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <div class="form-container">
            <form action="signin.php" method="POST">
                <h2>Sign In</h2>
                <?php if (isset($error)): ?>
                    <p class="message"><?php echo $error; ?></p>
                <?php endif; ?>
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
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                <a href="admin-login.php" class="button-admin">Sign in as admin</a>
            </form>
            <a href="index.php" class="button-back">Back to Home</a>
        </div>
    </main>
</body>
</html>

