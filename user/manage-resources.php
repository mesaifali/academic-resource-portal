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
                            <a href="/edit-resource.php?id=<?php echo $resource['id']; ?>" class="edit-btn">Edit</a>|
                            <a href="manage-resources.php?delete=<?php echo $resource['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

