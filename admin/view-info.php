<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT * FROM admin WHERE id='$admin_id'";
$result_admin = $conn->query($sql_admin);
$admin = $result_admin->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $errors = [];

    if (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters long";
    } elseif (!preg_match('/[A-Z]/', $new_password)) {
        $errors[] = "New password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $new_password)) {
        $errors[] = "New password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $new_password)) {
        $errors[] = "New password must contain at least one digit";
    } elseif (!preg_match('/[\W_]/', $new_password)) {
        $errors[] = "New password must contain at least one special character";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $sql_update_password = "UPDATE admin SET password='$hashed_password' WHERE id='$admin_id'";
        if ($conn->query($sql_update_password) === TRUE) {
            echo "Password updated successfully.";
        } else {
            echo "Error updating password.";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
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
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
                <div class="logo-links">
                    <div class="logo"><i class="fas fa-graduation-cap"></i> Admin Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="dashboard.php" class="menu-item "><i class="fas fa-home"></i> Dashboard</a>
                        <a href="manage-users.php" class="menu-item "><i class="fas fa-users"></i> Manage Users</a>
                        <a href="view-info.php" class="menu-item active-hover"><i class="fas fa-user"></i> View My Info</a>
                        <div class="submenu-title space-up">Resources</div>
                        <a href="manage-resources.php" class="menu-item"><i class="fas fa-book"></i> Manage Resources</a>
                        <a href="manage-approve-resources.php" class="menu-item"><i class="fas fa-check-circle"></i> Manage Status</a>
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
                <div class="analytics">Dashboard / Profile</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>

                <form action="" method="POST">

                    <h2>Profile Infomation</h2>
                    <div class="profile-container">
                        <div>
                            <label for="">Account type</label>
                            <input disabled value=<?php echo "admin" ?> />
                        </div>
                        <div>
                            <label for="">Email</label>
                            <input disabled value=<?php echo $admin['email']; ?> />
                        </div>
                        <div>
                            <label for="">Username</label>
                            <input disabled value=<?php echo $admin['username']; ?> />
                        </div>
                        <!-- <h3>Change Password</h3> -->
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="New Password"
                                required>
                        </div>
                        <button type="submit" name="update_password" class="button">Update Password</button>

                        <!-- <a class="button-back" href="dashboard.php">Back to Dashboard</a> -->
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