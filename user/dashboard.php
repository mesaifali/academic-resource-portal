<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>User Dashboard - Academic Resource Portals</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $user['name']; ?></h2>
        <ul>
            <li><a href="upload-resource.php">Upload Resources</a></li>
            <li><a href="manage-resources.php">Manage Resources</a></li>
            <li><a href="view-account.php">View My Account</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>


        <a class="button-back" href="../index.php">Back to Home</a>

    </div>
</body>
</html>


