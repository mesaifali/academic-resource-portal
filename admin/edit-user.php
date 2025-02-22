<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';

$errors = [];
$success = '';

$user_id = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage-users.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (strlen($name) < 3 || strlen($name) > 50) {
        $errors[] = "Name must be between 3 and 50 characters";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = "Username must be between 3 and 30 characters";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ?");
    $stmt->bind_param("ssi", $email, $username, $user_id);
    $stmt->execute();
    $duplicate_result = $stmt->get_result();

    if ($duplicate_result->num_rows > 0) {
        $errors[] = "Email or username already exists";
    }

    if (empty($errors)) {
        try {
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $errors[] = "Password must be at least 6 characters long";
                } elseif (!preg_match('/[A-Z]/', $new_password)) {
                    $errors[] = "Password must contain at least one uppercase letter";
                } elseif (!preg_match('/[a-z]/', $new_password)) {
                    $errors[] = "Password must contain at least one lowercase letter";
                } elseif (!preg_match('/[0-9]/', $new_password)) {
                    $errors[] = "Password must contain at least one digit";
                } elseif (!preg_match('/[\W_]/', $new_password)) {
                    $errors[] = "Password must contain at least one special character";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET name=?, email=?, username=?, password=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssi", $name, $email, $username, $hashed_password, $user_id);
                }
            } else {
                $sql = "UPDATE users SET name=?, email=?, username=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $name, $email, $username, $user_id);
            }

            if ($stmt->execute()) {
                $success = "User updated successfully!";
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            } else {
                $errors[] = "Error updating user: " . $conn->error;
            }
        } catch (Exception $e) {
            $errors[] = "Error: " . $e->getMessage();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Portal</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-message {
            color: #e53e3e;
            background-color: #fff5f5;
            border: 1px solid #fc8181;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .success-message {
            color: #2f855a;
            background-color: #f0fff4;
            border: 1px solid #68d391;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .profile-preview {
            margin: 20px 0;

            img {
                object-fit: contain;
            }
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-section {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
                <div class="logo-links">
                    <div class="logo"><i class="fas fa-graduation-cap"></i> Admin Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
                        <a href="manage-users.php" class="menu-item active-hover"><i class="fas fa-users"></i> Manage Users</a>
                        <a href="view-info.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
                        <div class="submenu-title space-up">Resources</div>
                        <a href="manage-resources.php" class="menu-item"><i class="fas fa-book"></i> Manage Resources</a>
                        <a href="manage-approve-resources.php" class="menu-item"><i class="fas fa-check-circle"></i> Manage Status</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <div class="content-area">
                <div class="analytics">Dashboard / Edit User</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem;"></div>
                <div class="form-container-resource">
                    <h2>Edit User</h2>

                    <div class="profile-section">
                        <div class="profile-preview">
                            <img src="<?php echo $user['profile_picture'] ? '../uploads/profile_picture/' . htmlspecialchars($user['profile_picture']) : 'https://via.placeholder.com/150'; ?>"
                                alt="Profile Picture"
                                class="profile-image">
                        </div>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="error-message">
                            <ul style="margin: 0; padding-left: 20px;">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="success-message">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form action="edit-user.php?id=<?php echo $user_id; ?>" method="POST">
                        <div class="grid-group">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password (leave blank to keep current)</label>
                                <input type="password" id="new_password" name="new_password">
                            </div>
                        </div>
                        <button type="submit" class="button">
                            Update User
                        </button>
                    </form>
                </div>
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