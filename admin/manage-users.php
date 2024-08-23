<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $sql_delete = "DELETE FROM users WHERE id='$user_id'";
    $conn->query($sql_delete);
    header("Location: manage-users.php");
}

$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Manage Users - Academic Resource Portal</title>
</head>
<body>
    <div class="dashboard-container">
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result_users->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td>
<a href="edit-user.php?id=<?php echo $user['id']; ?>" class="edit-btn">Edit</a>|
                        <a href="manage-users.php?delete=<?php echo htmlspecialchars($user['id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="delete-btn">Delete</a>
                         <!--   <a href="manage-users.php?delete=<?php echo $user['id']; ?>">Delete</a> -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
