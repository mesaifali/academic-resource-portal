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
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $sql_update_password = "UPDATE admin SET password='$new_password' WHERE id='$admin_id'";
    $conn->query($sql_update_password);
    echo "Password updated successfully.";
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
                    <div class="logo">Admin Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="dashboard.php" class="menu-item">Dashboard</a>
                        <a href="manage-users.php" class="menu-item">Manage Users</a>
                        <a href="view-info.php" class="menu-item">View My Info</a>
                        <div class="submenu-title space-up">Resources</div>
                        <a href="manage-resources.php" class="menu-item">Manage Resources</a>
                        <a href="manage-approve-resources.php" class="menu-item">Manage Status</a>
                    </div>
                </div>
                <div class="menu-item logout">Logout</div>
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