<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

// Handle resource deletion
if (isset($_GET['delete'])) {
    $resource_id = $_GET['delete'];
    $sql_delete = "DELETE FROM resources WHERE id='$resource_id'";
    $conn->query($sql_delete);
    header("Location: manage-resources.php");
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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Manage Resources - Academic Resource Portal</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Manage Resources</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Thumbnail</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($resource = $result_resources->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $resource['id']; ?></td>
                        <td><?php echo htmlspecialchars($resource['title']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($resource['type'])); ?></td>
                        <td><?php echo htmlspecialchars($resource['description']); ?></td>
                        <td><img src="../uploads/<?php echo htmlspecialchars($resource['thumbnail']); ?>" alt="Thumbnail" style="width: 100px; height: auto;"></td>
                        <td>
                            <a href="../uploads/<?php echo htmlspecialchars($resource['file_path']); ?>" download>Download</a>
                            <a href="edit-resource.php?id=<?php echo $resource['id']; ?>">Edit</a>
                            <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>" onclick="return confirm('Are you sure you want to delete this resource?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

