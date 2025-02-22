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

// Get resource ID from URL
$resource_id = isset($_GET['id']) ? $_GET['id'] : 0;
$user_id = $_SESSION['user_id'];

// Fetch existing resource data
$stmt = $conn->prepare("SELECT * FROM resources WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $resource_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage-resources.php");
    exit();
}

$resource = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);

    // Validate title
    if (empty($title)) {
        $errors[] = "Title is required";
    } elseif (strlen($title) < 3 || strlen($title) > 100) {
        $errors[] = "Title must be between 3 and 100 characters";
    }

    // Validate description
    if (empty($description)) {
        $errors[] = "Description is required";
    }

    // Validate type
    if (!in_array($type, $allowed_types)) {
        $errors[] = "Invalid resource type selected";
    }

    // Initialize file variables
    $file = $resource['file_path'];
    $thumbnail = $resource['thumbnail'];

    // Handle new file upload if provided
    if (!empty($_FILES['file']['name'])) {
        if ($_FILES['file']['size'] > $max_file_size) {
            $errors[] = "File size must be less than 10MB";
        }
        if (!in_array($_FILES['file']['type'], $allowed_file_types)) {
            $errors[] = "Only PDF and Word documents are allowed";
        }
    }

    // Handle new thumbnail upload if provided
    if (!empty($_FILES['thumbnail']['name'])) {
        if ($_FILES['thumbnail']['size'] > $max_image_size) {
            $errors[] = "Thumbnail size must be less than 2MB";
        }
        if (!in_array($_FILES['thumbnail']['type'], $allowed_image_types)) {
            $errors[] = "Only JPG, JPEG & PNG files are allowed for thumbnail";
        }
    }

    if (empty($errors)) {
        try {
            // Handle file uploads if new files are provided
            if (!empty($_FILES['file']['name'])) {
                $target_dir = "../uploads/$type/";
                if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
                $file = time() . '_' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $file);
            }

            if (!empty($_FILES['thumbnail']['name'])) {
                $thumb_dir = "../uploads/thumbnail/";
                if (!file_exists($thumb_dir)) mkdir($thumb_dir, 0777, true);
                $thumbnail = time() . '_' . basename($_FILES['thumbnail']['name']);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_dir . $thumbnail);
            }

            // Update database
            $sql = "UPDATE resources SET title=?, description=?, type=?, file_path=?, thumbnail=? WHERE id=? AND user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssii", $title, $description, $type, $file, $thumbnail, $resource_id, $user_id);

            if ($stmt->execute()) {
                $success = "Resource updated successfully!";
                // Refresh resource data with a new query
                $refresh_stmt = $conn->prepare("SELECT * FROM resources WHERE id = ? AND user_id = ?");
                $refresh_stmt->bind_param("ii", $resource_id, $user_id);
                $refresh_stmt->execute();
                $refresh_result = $refresh_stmt->get_result();
                $resource = $refresh_result->fetch_assoc();
                $refresh_stmt->close();
            } else {
                $errors[] = "Error updating resource: " . $conn->error;
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
    <title>Edit Resource - Academic Portal</title>
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

        .current-file {
            font-size: 0.9rem;
            color: #4a5568;
            margin-top: 5px;
        }

        .preview-container {
            margin-top: 10px;
        }

        .thumbnail-preview {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .download-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: white;
            text-decoration: none;
            background-color: #3182ce;
            padding: 5px 10px;
            border: 1px solid #3182ce;
            border-radius: 4px;
            margin-top: 5px;
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
                        <a href="upload-resource.php" class="menu-item "><i class="fas fa-upload"></i> Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item active-hover"><i class="fas fa-tasks"></i> Manage Resources</a>
                        <div class="submenu-title space-up">Account</div>

                        <a href="view-account.php" class="menu-item"><i class="fas fa-user"></i> View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        <div class="main-content">
            <div class="content-area">
                <div class="analytics">Dashboard / Edit Resource</div>
                <div style="border: 1px solid #d3d3d3; margin-top: 1rem;"></div>
                <div class="form-container-resource">
                    <h2><i class="fas fa-edit"></i> Edit Resource</h2>

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

                    <form action="edit-resource.php?id=<?php echo $resource_id; ?>" method="POST" enctype="multipart/form-data">
                        <div class="grid-group">
                            <div class="form-group">
                                <label for="title">Resource Title</label>
                                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="type"> Resource Type</label>
                                <select id="type" name="type" required>
                                    <option value="book" <?php echo $resource['type'] == 'book' ? 'selected' : ''; ?>>Book</option>
                                    <option value="note" <?php echo $resource['type'] == 'note' ? 'selected' : ''; ?>>Note</option>
                                    <option value="question" <?php echo $resource['type'] == 'question' ? 'selected' : ''; ?>>Question</option>
                                </select>
                            </div>
                            <div class="form-group col-span-all">
                                <label for="description"> Resource Description</label>
                                <textarea id="description" name="description" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file">Update File (Optional)</label>
                                <input type="file" id="file" name="file"
                                    accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                <div class="current-file">
                                    Current file: <?php echo htmlspecialchars($resource['file_path']); ?>
                                    <?php if ($resource['file_path']): ?>
                                        <br>
                                        <a href="../uploads/<?php echo $resource['type'] . '/' . htmlspecialchars($resource['file_path']); ?>"
                                            class="download-link"
                                            download>
                                            <i class="fas fa-download"></i> Download File
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="thumbnail"> Update Thumbnail (Optional)</label>
                                <input type="file" id="thumbnail" name="thumbnail"
                                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                                    onchange="previewThumbnail(this);">
                                <div class="current-file">
                                    Current thumbnail: <?php echo htmlspecialchars($resource['thumbnail']); ?>
                                    <?php if ($resource['thumbnail']): ?>
                                        <div class="preview-container">
                                            <img src="../uploads/thumbnail/<?php echo htmlspecialchars($resource['thumbnail']); ?>"
                                                alt="Thumbnail Preview"
                                                class="thumbnail-preview">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="button">
                            Update Resource
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

        function previewThumbnail(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewContainer = input.parentElement.querySelector('.preview-container');
                    if (!previewContainer) {
                        previewContainer = document.createElement('div');
                        previewContainer.className = 'preview-container';
                        input.parentElement.appendChild(previewContainer);
                    }
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" alt="Thumbnail Preview" class="thumbnail-preview">
                    `;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>