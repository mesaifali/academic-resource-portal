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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Manage Resources - Academic Resource Portal</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Manage Resources</h2>
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
                        <td><?php echo htmlspecialchars($resource['description']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($resource['file_path'])); ?></td>

                        <td>
                            <?php if ($resource['thumbnail']) { ?>
        <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>" alt="Thumbnail" style="width: 100px; height: auto;">
                            <?php } else { ?>
                                No Thumbnail
                            <?php } ?>
                        </td>
                        <td>
                            <!-- Direct download link for admins -->
<a href="../download.php?file=<?php echo urlencode($resource['file_path']); ?>&type=<?php echo urlencode($resource['type']); ?>">Download</a>|


                   <a href="/edit-resource.php?id=<?php echo htmlspecialchars($resource['id']); ?>">Edit</a>|

                            <a href="manage-resources.php?delete=<?php echo htmlspecialchars($resource['id']); ?>" onclick="return confirm('Are you sure you want to delete this resource?');" class="delete-btn">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
