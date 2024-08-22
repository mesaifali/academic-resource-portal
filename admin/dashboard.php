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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Dashboard - Academic Resource Portal</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <p>Total Registered Users: <?php echo $total_users; ?></p>
        <p>Total Resources Uploaded: <?php echo $total_resources; ?></p>
        <ul>
            <li><a href="manage-users.php">Manage Users</a></li>
            <li><a href="manage-resources.php">Manage Resources</a></li>
            <li><a href="approve-decline.php">Approve/Decline Resources</a></li>
            <li><a href="view-info.php">View My Info</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
            <a class="button-back" href="../index.php">Back to Home</a>
    </div>
</body>
</html>

