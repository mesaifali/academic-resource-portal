<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $resource_id = intval($_GET['delete']);
    
    // First get the resource details
    $stmt = $conn->prepare("SELECT file_path, thumbnail, type FROM resources WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $resource_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resource = $result->fetch_assoc();
    
    if ($resource) {
        $file_path = "../uploads/" . $resource['type'] . "/" . $resource['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        if ($resource['thumbnail']) {
            $thumbnail_path = "../uploads/thumbnail/" . $resource['thumbnail'];
            if (file_exists($thumbnail_path)) {
                unlink($thumbnail_path);
            }
        }
        
        $stmt = $conn->prepare("DELETE FROM resources WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $resource_id, $user_id);
        $stmt->execute();
    }
    
    header("Location: manage-resources.php");
    exit();
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
    <title>Manage Resources - Academic Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
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
                        <a href="manage-resources.php" class="menu-item active-hover"><i class="fas fa-tasks"></i> Manage Resources</a>
                        <div class="submenu-title space-up">Account</div>

                        <a href="view-account.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
                                    <a href="edit-resource.php?id=<?php echo $resource['id']; ?>"
                                        class="badge badge-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this resource?');"
                                        class="badge badge-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
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