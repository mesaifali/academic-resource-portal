<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

// Handle resource deletion
if (isset($_GET['delete'])) {
    $resource_id = intval($_GET['delete']);
    $sql_delete = "DELETE FROM resources WHERE id='$resource_id'";
    $conn->query($sql_delete);
    header("Location: manage-resources.php");
    exit();
}


// Fetch approved resources
$sql_resources = "SELECT * FROM resources WHERE status='approved'";
$result_resources = $conn->query($sql_resources);
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
                <a href="logout.php" class="menu-item logout">Logout</a>
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
                            <th>Type</th>
                            <th>Description</th>
                            <th>File Name</th>
                            <th>Thumbnail</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($resource = $result_resources->fetch_assoc()) { ?>
                            <tr>
                                <?php $thumbnail = 'uploads/thumbnail/' . htmlspecialchars($resource['thumbnail']); ?>
                                <td><?php echo htmlspecialchars($resource['id']); ?></td>
                                <td><?php echo htmlspecialchars($resource['title']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($resource['type'])); ?></td>
                                <td style="max-width: 70px; overflow:hidden">
                                    <?php echo htmlspecialchars($resource['description']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($resource['file_path'])); ?></td>

                                <td>
                                    <?php if ($resource['thumbnail']) { ?>
                                        <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>"
                                            alt="Thumbnail" style="height:70px; width:70px; object-fit:cover;">
                                    <?php } else { ?>
                                        No Thumbnail
                                    <?php } ?>
                                </td>
                                <td>
                                    <!-- Direct download link for admins -->
                                    <a
                                        href="../download.php?file=<?php echo urlencode($resource['file_path']); ?>&type=<?php echo urlencode($resource['type']); ?>">Download</a>|


                                    <a
                                        href="edit-resource.php?id=<?php echo htmlspecialchars($resource['id']); ?>">Edit</a>|

                                    <a href="manage-resources.php?delete=<?php echo htmlspecialchars($resource['id']); ?>"
                                        onclick="return confirm('Are you sure you want to delete this resource?');"
                                        class="delete-btn">Delete</a>
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