<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $errors[] = "Username or Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one digit";
    } elseif (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: user/upload-resource.php");
            exit();
        } else {
            $errors[] = "Invalid username or password";
        }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Sign In - Academic Resource Portal</title>
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

        .sign-in-icon {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <header>
        <!-- <?php include 'includes/header.php'; ?> -->
    </header>
    <main>
        <div class="form-container">
            <form action="signin.php" method="POST">
                <h2><i class="fas fa-user-circle"></i> Sign In</h2>
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <ul style="margin: 0; padding-left: 10px;">
                            <?php foreach ($errors as $error): ?>
                                <i class="fas fa-exclamation-circle"></i>   <?php echo htmlspecialchars($error); ?> 
                            <?php endforeach; ?>
                        </ul>
                    </div>  
                <?php endif; ?>
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username or Email:</label>
                    <input type="text" name="username" id="username" placeholder="Enter your username or email"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="button">
                    <i class="fas fa-sign-in-alt sign-in-icon"></i>Sign In
                </button>
                <hr class="dashed">
                <p style="text-align: center;">Don't have an account? <a href="signup.php">Sign Up</a></p>
                <a href="admin-login.php" class="button-admin">
                    Sign in as admin
                </a>
            </form>
            <a href="index.php" class="button-back">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </main>
</body>

</html>