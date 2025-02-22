<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

$errors = [];
$success = '';
$allowed_types = ['book', 'note', 'question'];
$allowed_file_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$allowed_image_types = ['image/jpeg', 'image/png', 'image/jpg'];
$max_file_size = 10 * 1024 * 1024; // 10MB
$max_image_size = 2 * 1024 * 1024; // 2MB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);

    if (empty($title)) {
        $errors[] = "Title is required";
    }

    if (empty($description)) {
        $errors[] = "Description is required";
    }

    if (!in_array($type, $allowed_types)) {
        $errors[] = "Invalid resource type selected";
    }

    // Validate main file
    if (!isset($_FILES['file']) || $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Resource file is required";
    } else {
        if ($_FILES['file']['size'] > $max_file_size) {
            $errors[] = "File size must be less than 10MB";
        }
        if (!in_array($_FILES['file']['type'], $allowed_file_types)) {
            $errors[] = "Only PDF and Word documents are allowed";
        }
    }
    // Validate thumbnail
    if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Thumbnail image is required";
    } else {
        if ($_FILES['thumbnail']['size'] > $max_image_size) {
            $errors[] = "Thumbnail size must be less than 2MB";
        }
        if (!in_array($_FILES['thumbnail']['type'], $allowed_image_types)) {
            $errors[] = "Only JPG, JPEG & PNG files are allowed for thumbnail";
        }
    }

    if (empty($errors)) {
        try {
            $target_dir = "../uploads/$type/";
            $thumb_dir = "../uploads/thumbnail/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            if (!file_exists($thumb_dir)) mkdir($thumb_dir, 0777, true);

            $file = time() . '_' . basename($_FILES['file']['name']);
            $thumbnail = time() . '_' . basename($_FILES['thumbnail']['name']);

            $target_file = $target_dir . $file;
            $thumb_file = $thumb_dir . $thumbnail;

            // Move files
            if (
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) &&
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_file)
            ) {

                $sql = "INSERT INTO resources (user_id, title, description, file_path, thumbnail, type) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssss", $user_id, $title, $description, $file, $thumbnail, $type);

                if ($stmt->execute()) {
                    $success = "Resource uploaded successfully!";
                    header("Location: manage-resources.php?success=1");
                    exit();
                } else {
                    $errors[] = "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                $errors[] = "Failed to upload files";
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
    <title>Responsive Dashboard</title>
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
    </style>
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
                <div class="logo-links">
                    <div class="logo"><i class="fas fa-graduation-cap"></i>User Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="upload-resource.php" class="menu-item active-hover"><i class="fas fa-upload"></i> Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item"><i class="fas fa-tasks"></i> Manage Resources</a>
                        <div class="submenu-title space-up">Account</div>

                        <a href="view-account.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <!-- <div class="header">
                <input type="text" class="search-bar" placeholder="Search...">
                <div class="user-profile">
                    <img src="https://via.placeholder.com/32" alt="User Avatar" class="user-avatar">
                    <span>Tom Cook</span>
                </div>
            </div> -->
            <div class="content-area">
                <div class="analytics">Dashboard / Upload Resource</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem; "></div>
                <div class="form-container-resource">
                    <h2>Upload Resource</h2>

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

                    <form action="upload-resource.php" method="POST" enctype="multipart/form-data">
                        <div class="grid-group">
                            <div class="form-group">
                                <label for="title">Resource Title</label>
                                <input type="text" id="title" name="title" placeholder="Resource Title" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Resource Type</label>
                                <select id="type" name="type" required>
                                    <option value="book">Book</option>
                                    <option value="note">Note</option>
                                    <option value="question">Question</option>
                                </select>
                            </div>
                            <div class="form-group col-span-all">
                                <label for="description">Resource Description</label>
                                <textarea id="description" name="description" placeholder="Resource Description"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="file">Upload File</label>
                                <input type="file"
                                    id="file"
                                    name="file"
                                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="thumbnail">Upload Thumbnail</label>
                                <input type="file"
                                    id="thumbnail"
                                    name="thumbnail"
                                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="button">Submit</button>
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