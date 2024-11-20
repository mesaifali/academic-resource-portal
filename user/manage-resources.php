<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $resource_id = $_GET['delete'];
    $sql_delete = "DELETE FROM resources WHERE id='$resource_id' AND user_id='$user_id'";
    $conn->query($sql_delete);
    header("Location: manage-resources.php");
}

$sql_user_resources = "SELECT * FROM resources WHERE user_id='$user_id'";
$result_user_resources = $conn->query($sql_user_resources);
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
                        <!-- <a href="dashboard.php" class="menu-item">Dashboard</a> -->
                        <a href="upload-resource.php" class="menu-item">Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item">Manage Resources</a>
                        <a href="view-account.php" class="menu-item">View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout">Logout</a>
            </div>
        </div>
        <div class="main-content">
            <div class="content-area">
                <div class="analytics">Dashboard / Manage Resources</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; margin-bottom: 1rem; "></div>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($resource = $result_user_resources->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $resource['id']; ?></td>
                                <td><?php echo $resource['title']; ?></td>
                                <td><?php echo ucfirst($resource['type']); ?></td>
                                <td><?php echo ucfirst($resource['status']); ?></td>
                                <td>
                                    <a href="edit-resource.php?id=<?php echo $resource['id']; ?>" class="edit-btn">Edit</a>|
                                    <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>">Delete</a>
                                </td>
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