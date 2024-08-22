<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT * FROM admin WHERE id='$admin_id'";
$result_admin = $conn->query($sql_admin);
$admin = $result_admin->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $sql_update_password = "UPDATE admin SET password='$new_password' WHERE id='$admin_id'";
    $conn->query($sql_update_password);
    echo "Password updated successfully.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>View My Info - Academic Resource Portal</title>
</head>
<body>
    <div class="form-container">
        <h2>View My Info</h2>
       <p>Name: admin </p>
       <!-- <p>Name: <?php echo $admin['name']; ?></p> -->
        <p>Email: <?php echo $admin['email']; ?></p>
        <p>Username: <?php echo $admin['username']; ?></p>

        <form action="" method="POST">
            <h3>Change Password</h3>
           <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
                 </div>
            <button type="submit" name="update_password" class="button">Update Password</button>
        </form>

        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
