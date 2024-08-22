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

    $thumb_dir = "../uploads/thumbnails/";
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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Upload Resource - Academic Resource Portal</title>
</head>
<body>
    <div class="form-container">
        <form action="upload-resource.php" method="POST" enctype="multipart/form-data">
            <h2>Upload Resource</h2>
            <div class="form-group">
                <label for="title">Resource Title</label>
                <input type="text" id="title" name="title" placeholder="Resource Title" required>
            </div>
            <div class="form-group">
                <label for="description">Resource Description</label>
                <textarea id="description" name="description" placeholder="Resource Description" required></textarea>
            </div>
            <div class="form-group">
                <label for="type">Resource Type</label>
                <select id="type" name="type" required>
                    <option value="book">Book</option>
                    <option value="note">Note</option>
                    <option value="question">Question</option>
                </select>
            </div>
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" id="file" name="file" required>
            </div>
            <div class="form-group">
                <label for="thumbnail">Upload Thumbnail</label>
                <input type="file" id="thumbnail" name="thumbnail" required>
            </div>
            <button type="submit" class="button">Submit</button>
        </form>
        <a class="button-back" href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

