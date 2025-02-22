<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

if (isset($_GET['approve'])) {
    $resource_id = $_GET['approve'];
    $sql_approve = "UPDATE resources SET status='approved' WHERE id='$resource_id'";
    $conn->query($sql_approve);
    header("Location: approve-decline.php");
}

if (isset($_GET['decline'])) {
    $resource_id = $_GET['decline'];
    $sql_decline = "UPDATE resources SET status='declined' WHERE id='$resource_id'";
    $conn->query($sql_decline);
    header("Location: manage-approve-resources.php");
}

$sql_pending_resources = "SELECT * FROM resources WHERE status='pending'";
$result_pending_resources = $conn->query($sql_pending_resources);
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
                        <a href="view-info.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
                        <div class="submenu-title space-up">Resources</div>
                        <a href="manage-resources.php" class="menu-item"><i class="fas fa-book"></i> Manage Resources</a>
                        <a href="manage-approve-resources.php" class="menu-item active-hover"><i class="fas fa-check-circle"></i> Manage Status</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">

            <div class="content-area">
                <div class="analytics">Dashboard / Manage Resources</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>

                <table class="resources-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Thumbnail</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($result_pending_resources->num_rows > 0) { ?>
                            <?php while ($resource = $result_pending_resources->fetch_assoc()) { ?>
                                <tr>
                                    <?php $thumbnail = 'uploads/thumbnail/' . htmlspecialchars($resource['thumbnail']); ?>
                                    <td><?php echo htmlspecialchars($resource['id']); ?></td>
                                    <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                    <td>
                                        <?php if ($resource['thumbnail']) { ?>
                                            <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>"
                                                alt="Thumbnail" style="height:70px; width:70px; object-fit:cover;">
                                        <?php } else { ?>
                                            No Thumbnail
                                        <?php } ?>
                                    </td>
                                    <td><?php echo ucfirst(htmlspecialchars($resource['type'])); ?></td>
                                    <td>
                                        <a href="approve-decline.php?approve=<?php echo $resource['id']; ?>" 
                                           class="badge badge-edit" 
                                           onclick="return confirm('Are you sure you want to approve this resource?');">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="approve-decline.php?decline=<?php echo $resource['id']; ?>" 
                                           class="badge badge-delete"
                                           onclick="return confirm('Are you sure you want to decline this resource?');">
                                            <i class="fas fa-times"></i> Decline
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No pending resources</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

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