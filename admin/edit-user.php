<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql_user = "SELECT * FROM users WHERE id='$user_id'";
    $result_user = $conn->query($sql_user);
    if ($result_user->num_rows === 0) {
        // Redirect if user not found
        header("Location: manage-users.php?error=user_not_found");
        exit();
    }
    $user = $result_user->fetch_assoc();
} else {
    header("Location: manage-users.php");
    exit();
}

$update_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation
    if (empty($name) || empty($email)) {
        $error_message = 'Name and Email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        // Prepare the SQL update statement
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET name='$name', email='$email', password='$password' WHERE id='$user_id'";
        } else {
            $sql_update = "UPDATE users SET name='$name', email='$email' WHERE id='$user_id'";
        }

        if ($conn->query($sql_update)) {
            $update_success = true;
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }

    $conn->close();
    
    if ($update_success) {
        // Only redirect if update was successful
        header("Location: manage-users.php?update=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Edit User - Academic Resource Portal</title>
    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update this user?");
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="edit-user.php?id=<?php echo $user['id']; ?>" method="post" class="form-group" onsubmit="return confirmUpdate();">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (leave blank if not changing):</label>
                <input type="password" id="password" name="password" placeholder="Enter a password">
            </div>

            <button type="submit" class="button">Update</button>
            <a class="button-back" href="manage-users.php">Back to Manage Users</a>
        </form>
    </div>
</body>
</html>

