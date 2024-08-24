<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $sql_update_password = "UPDATE users SET password='$new_password' WHERE id='$user_id'";
        $conn->query($sql_update_password);
        $message = "Password updated successfully.";
    } elseif (isset($_POST['update_profile_picture'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../uploads/profile_picture/";
        $target_file = $target_dir . basename($profile_picture);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

        $sql_update_picture = "UPDATE users SET profile_picture='$profile_picture' WHERE id='$user_id'";
        $conn->query($sql_update_picture);
        $message = "Profile picture updated successfully.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>View My Account - Academic Resource Portal</title>
</head>
<body>
    <div class="form-container">
        <h2>View My Account</h2>
        <div class="profile-section">
            <div class="profile-picture">
                <?php if (!empty($user['profile_picture'])) { ?>
                    <img src="../uploads/profile_picture/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                <?php } else { ?>
                    <img src="../assets/img/default-profile.png" alt="Default Profile Picture" style="width: 150px;height: 150px;border-radius: 50%;">
                <?php } ?>
            </div>
            <div class="profile-info">
                <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            </div>
        </div>
        
        <?php if (isset($message)) { ?>
            <p class="message"><?php echo $message; ?></p>
        <?php } ?>

        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Change Password</h3>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required>
            </div>
            <button type="submit" name="update_password" class="button">Update Password</button>
        </form>

        <form action="" method="POST" enctype="multipart/form-data" class="form-section">
            <h3>Update Profile Picture</h3>
            <div class="form-group">
                <label for="profile_picture">Choose New Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" required>
            </div>
            <button type="submit" name="update_profile_picture" class="button">Update Profile Picture</button>
        </form>
               <hr class="dashed">
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
