<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $file = $_FILES['file']['name'];
    $thumbnail = $_FILES['thumbnail']['name'];

    $target_dir = "../uploads/$type/";
    $target_file = $target_dir . basename($file);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

    $thumb_dir = "../uploads/thumbnail/";
    $thumb_file = $thumb_dir . basename($thumbnail);
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumb_file);

    $sql = "INSERT INTO resources (user_id, title, description, file_path, thumbnail, type) 
            VALUES ('$user_id', '$title', '$description', '$file', '$thumbnail', '$type')";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage-resources.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
</head>

<body>
    <div class="container">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar-container">
            <div class="sidebar" id="sidebar">
            <div class="logo-links">
                    <div class="logo">User Dashboard</div>
                    <div class="links">
                        <div class="submenu-title">Main</div>
                        <a href="dashboard.php" class="menu-item">Dashboard</a>
                        <a href="upload-resource.php" class="menu-item">Upload Resource</a>
                        <a href="manage-resources.php" class="menu-item">Manage Resources</a>
                        <a href="view-account.php" class="menu-item">View My Info</a>
                    </div>
                </div>
                <a href="logout.php" class="menu-item logout">Logout</a>
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
                                <input type="file" id="file" name="file" required>
                            </div>
                            <div class="form-group">
                                <label for="thumbnail">Upload Thumbnail</label>
                                <input type="file" id="thumbnail" name="thumbnail" required>
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