<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

// Count the number of registered users and resources uploaded
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

$sql_resources = "SELECT COUNT(*) AS total_resources FROM resources";
$result_resources = $conn->query($sql_resources);
$total_resources = $result_resources->fetch_assoc()['total_resources'];


// Get the number of pending resources
$sql_pending_resources = "SELECT COUNT(*) AS total_pending FROM resources WHERE status='pending'";
$result_pending_resources = $conn->query($sql_pending_resources);
$total_pending = $result_pending_resources->fetch_assoc()['total_pending'];

// Add this query before $conn->close();
$sql_recent = "SELECT r.*, u.username 
               FROM resources r 
               JOIN users u ON r.user_id = u.id 
               ORDER BY r.created_at DESC 
               LIMIT 5";
$result_recent = $conn->query($sql_recent);

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
                        <a href="dashboard.php" class="menu-item active-hover"><i class="fas fa-home"></i> Dashboard</a>
                        <a href="manage-users.php" class="menu-item "><i class="fas fa-users"></i> Manage Users</a>
                        <a href="view-info.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
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
                <div class="analytics">Dashboard / Analytics</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>

                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <div class="card-icon blue">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Users</h3>
                            <h2><?php echo $total_users; ?>+</h2>
                            <p><i class="fas fa-chart-line"></i> Active Members</p>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon green">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Resources</h3>
                            <h2><?php echo $total_resources; ?>+</h2>
                            <p><i class="fas fa-upload"></i> Uploaded Resources</p>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="card-icon orange">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="card-info">
                            <h3>Pending Resources</h3>
                            <h2><?php echo $total_pending; ?></h2>
                            <p><i class="fas fa-hourglass-half"></i> Awaiting Approval</p>
                        </div>
                    </div>
                </div>

                <div class="recent-activities">
                    <h2><i class="fas fa-history"></i> Recent Activities</h2>
                    <div class="table-container">
                        <table class="activities-table">
                            <thead>
                                <tr>
                                    <th>Resource</th>
                                    <th>Type</th>
                                    <th>Uploaded By</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($activity = $result_recent->fetch_assoc()) { ?>
                                    <tr>
                                        <td><i class="fas fa-file-alt"></i> <?php echo htmlspecialchars($activity['title']); ?></td>
                                        <td><i class="fas fa-folder"></i> <?php echo ucfirst(htmlspecialchars($activity['type'])); ?></td>
                                        <td><i class="fas fa-user"></i> <?php echo htmlspecialchars($activity['username']); ?></td>
                                        <td>
                                            <?php if($activity['status'] == 'approved'): ?>
                                                <span class="status-approved"><i class="fas fa-check-circle"></i> Approved</span>
                                            <?php else: ?>
                                                <span class="status-pending"><i class="fas fa-clock"></i> Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($activity['created_at'])); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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