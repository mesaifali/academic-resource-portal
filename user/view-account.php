<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (!password_verify($current_password, $user['password'])) {
            $error = "Current password is incorrect";
        } elseif (strlen($new_password) < 6) {
            $error = "New password must be at least 6 characters long";
        } elseif (!preg_match('/[A-Z]/', $new_password)) {
            $error = "New password must contain at least one uppercase letter";
        } elseif (!preg_match('/[a-z]/', $new_password)) {
            $error = "New password must contain at least one lowercase letter";
        } elseif (!preg_match('/[0-9]/', $new_password)) {
            $error = "New password must contain at least one digit";
        } elseif (!preg_match('/[\W_]/', $new_password)) {
            $error = "New password must contain at least one special character";
        } elseif ($new_password !== $confirm_password) {
            $error = "New password and confirmation password do not match";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $sql_update = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
            if ($conn->query($sql_update) === TRUE) {
                $success = "Password updated successfully";
            } else {
                $error = "Error updating password";
            }
        }
    }
} elseif (isset($_POST['update_profile_picture'])) {
    if (isset($_FILES['profile_picture'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['profile_picture']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_types)) {
            $error = "Profile picture must be JPG, JPEG or PNG";
        } elseif ($_FILES['profile_picture']['size'] > $max_size) {
            $error = "Profile picture must be less than 2MB";
        } else {
            $image_info = getimagesize($_FILES['profile_picture']['tmp_name']);
            if ($image_info === false) {
                $error = "Invalid image file";
            } elseif ($image_info[0] > 2000 || $image_info[1] > 2000) {
                $error = "Image dimensions should not exceed 2000x2000 pixels";
            } else {
                $profile_picture = $_FILES['profile_picture']['name'];
                $target_dir = "../uploads/profile_picture/";
                $target_file = $target_dir . basename($profile_picture);
                move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

                $sql_update_picture = "UPDATE users SET profile_picture='$profile_picture' WHERE id='$user_id'";
                if ($conn->query($sql_update_picture) === TRUE) {
                    $success = "Profile picture updated successfully.";
                } else {
                    $error = "Error updating profile picture";
                }
            }
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
    <title>Responsive Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .error-message,
        .success-message {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            color: #28a745;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .message-icon {
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
                <div class="logo-links">
                    <div class="logo"><i class="fas fa-graduation-cap"></i>User Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="upload-resource.php" class="menu-item "><i class="fas fa-upload"></i> Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item"><i class="fas fa-tasks"></i> Manage Resources</a>
                        <div class="submenu-title space-up">Account</div>

                        <a href="view-account.php" class="menu-item active-hover"><i class="fas fa-user"></i> View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <!-- <div class="header">
                <input type="text" class="search-bar" placeholder="Search...">
                <div class="user-profile">
                    <img src="https://via.placeholder.com/32" alt="User Avatar" class="user-avatar">
                    <span>Tom Cook</span>
                </div>
            </div> -->
            <div class="content-area">
                <div class="analytics">Dashboard / Upload Resource</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>

                <form action="" method="POST">

                    <h2>Profile Infomation</h2>
                    <?php if ($error): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle message-icon"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle message-icon"></i>
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    <div class="user-profile-container">
                        <div>
                            <label for="">Account type</label>
                            <input disabled value=<?php echo $user['name']; ?> />
                        </div>
                        <div>
                            <label for="">Email</label>
                            <input disabled value=<?php echo $user['email']; ?> />
                        </div>
                        <div>
                            <label for="">Username</label>
                            <input disabled value=<?php echo $user['username']; ?> />
                        </div>



                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password"
                                placeholder="Current Password"
                                name="current_password" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 characters"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                placeholder="Confirm New Password"
                                required>

                        </div>
                        <button type="submit" name="update_password" class="button primary-button">Update
                            Password</button>

                        <!-- <a class="button-back" href="dashboard.php">Back to Dashboard</a> -->
                    </div>
                </form>

                <h2>Update Profile picture</h2>
                <div class="profile-picture-container">
                    <img src="<?php echo $user['profile_picture'] ? '../uploads/profile_picture /' . htmlspecialchars($user['profile_picture']) : 'https://via.placeholder.com/150'; ?>"
                        alt="Profile Picture">
                </div>

                <form action="" method="POST" enctype="multipart/form-data" class="grid-group">
                    <div class="form-group">
                        <label for="profile_picture">Choose New Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture">
                    </div>
                    <div class="profile-button">
                        <button type="submit" name="update_profile_picture" class="button primary-button">Update Profile
                            Picture</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
</body>

</html>