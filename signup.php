<?php
include 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "Name is required";
    }

    $username = trim($_POST['username']);
    if (empty($username)) {
        $errors[] = "Username is required";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Username already exists";
        }
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Email already registered";
        }
    }

    $phone = trim($_POST['phone']);
    if (!empty($phone)) {
        if (!is_numeric($phone)) {
            $errors[] = "Phone number must contain only digits";
        } elseif (strlen($phone) != 10) {
            $errors[] = "Phone number must be exactly 10 digits";
        }
    }

    $password = $_POST['password'];
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
    if (isset($_FILES['profile_picture'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['profile_picture']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_types)) {
            $errors[] = "Profile picture must be JPG, JPEG or PNG";
        }
        if ($_FILES['profile_picture']['size'] > $max_size) {
            $errors[] = "Profile picture must be less than 2MB";
        }

        $image_info = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($image_info === false) {
            $errors[] = "Invalid image file";
        } else {
            if ($image_info[0] > 2000 || $image_info[1] > 2000) {
                $errors[] = "Image dimensions should not exceed 2000x2000 pixels";
            }
        }
    }

    if (empty($errors)) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $profile_picture = $_FILES['profile_picture']['name'];

        $target_dir = "uploads/profile_picture/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

        $sql = "INSERT INTO users (name, username, email, phone, profile_picture, password) 
                VALUES ('$name', '$username', '$email', '$phone', '$profile_picture', '$password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: signin.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Sign Up - Academic Resource Portal</title>
    <style>
        .error-message {
            color: #e53e3e;
            background-color: #fff5f5;
            border: 1px solid #fc8181;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 10px;
            margin-bottom: 15px;

            div {
                margin-right: .5rem;
            }
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

        .form-group input[type="file"] {
            padding-right: 35px;
        }
    </style>
</head>

<body>
    <main style="">
        <div class="form-container">
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <h2><i class="fas fa-user-plus"></i> Sign Up</h2>
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <?php while ($error = array_shift($errors)): ?>
                            <div><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?><br></div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Full Name:</label>
                    <input type="text" name="name" id="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="username"><i class="fas fa-at"></i> Username:</label>
                    <input type="text" name="username" id="username" placeholder="Choose a username" required>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Phone Number:</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="profile_picture"><i class="fas fa-image"></i> Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password:</label>
                    <input type="password" name="password" id="password" placeholder="Choose a password" required>
                </div>
                <button type="submit" class="button">
                    <i class="fas fa-user-plus"></i> Sign Up
                </button>
                <hr class="dashed">
                <p style="text-align: center;">
                    <i class="fas fa-sign-in-alt"></i> Already have an account?
                    <a href="signin.php">Sign In</a>
                </p>
            </form>
            <a href="index.php" class="button-back">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </main>
</body>

</html>