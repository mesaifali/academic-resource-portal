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
$user_resources_sql = "SELECT COUNT(*) as total_resources FROM resources where user_id='$user_id' ";
$fetched_user_resources = $conn->query($user_resources_sql);
$todal_user_resources = $fetched_user_resources->fetch_assoc();
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
                <a href="logout.php" class="menu-item logout">Logout</a>
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
                <div class="analytics">Dashboard </div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>
                <div>
                </div>
                <div class="dashboard-cards">
                    <div class="dashboard-card ">
                        <div class="card-content">
                            <h3>Total Resources</h3>
                            <p class="card-metric"><?php echo $todal_user_resources['total_resources']; ?></p>
                        </div>
                    </div>

                    <!-- <div class="quick-actions">
                        <div class="card-content">
                            <h3>Quick Actions</h3>
                            <div class="action-buttons">
                                <a href="upload-resource.php" class="action-btn">- Upload New Resource</a>
                                <a href="manage-resource.php" class="action-btn">- See Resources</a>
                                <a href="view-account.php" class="action-btn">- Edit Profile</a>
                                <a href="logout.php" class="action-btn">- Edit Profile</a>
                            </div>
                        </div>
                    </div> -->
                </div>

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