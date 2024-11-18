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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $sql_update_password = "UPDATE users SET password='$new_password' WHERE id='$user_id'";
        $conn->query($sql_update_password);
        $message = "Password updated successfully.";
    } elseif (isset($_POST['update_profile_picture'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../uploads/profile_picture/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

        $sql_update_picture = "UPDATE users SET profile_picture='$profile_picture' WHERE id='$user_id'";
        $conn->query($sql_update_picture);
        $message = "Profile picture updated successfully.";
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
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
                <div class="logo-links">
                    <div class="logo">User Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="dashboard.php" class="menu-item">Dashboard</a>
                        <a href="upload-resource.php" class="menu-item">Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item">Manage Resources</a>
                        <a href="view-account.php" class="menu-item">View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout">View My Info</a>
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
                        <!-- <h3>Change Password</h3> -->
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="New Password"
                                required>
                        </div>
                        <button type="submit" name="update_password" class="button primary-button">Update
                            Password</button>

                        <!-- <a class="button-back" href="dashboard.php">Back to Dashboard</a> -->
                    </div>
                </form>
                <h2>Update Profile picture</h2>

                <form action="" method="POST" enctype="multipart/form-data" class="grid-group">
                    <div class="form-group">
                        <label for="profile_picture">Choose New Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" required>
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