<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if ID is set and valid
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        echo "Invalid resource ID!";
        exit;
    }

    $resource_id = intval($_POST['id']);
    $title = sanitizeInput($_POST['title']);
    $type = sanitizeInput($_POST['type']);
    $description = sanitizeInput($_POST['description']);

    // Debugging output
    echo "Resource ID: $resource_id<br>";
    echo "Title: $title<br>";
    echo "Type: $type<br>";
    echo "Description: $description<br>";

    // Handle file upload
    $file_path = ''; // Default to empty if no new file is uploaded
    $thumbnail_path = ''; // Default to empty if no new thumbnail is uploaded

    if (!empty($_FILES['file']['name'])) {
        $file_name = basename($_FILES['file']['name']);
        $file_path = 'uploads/' . $type . '/' . $file_name;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            echo "File uploaded successfully: $file_path<br>";
        } else {
            echo "Failed to upload file.<br>";
        }
    }

    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail_name = basename($_FILES['thumbnail']['name']);
        $thumbnail_path = 'uploads/thumbnail/' . $thumbnail_name;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path)) {
            echo "Thumbnail uploaded successfully: $thumbnail_path<br>";
        } else {
            echo "Failed to upload thumbnail.<br>";
        }
    }

    // Update query
    $sql = "UPDATE resources SET title = '$title', type = '$type', description = '$description'";

    if (!empty($file_path)) {
        $sql .= ", file_path = '$file_path'";
    }

    if (!empty($thumbnail_path)) {
        $sql .= ", thumbnail = '$thumbnail_path'";
    }

    $sql .= " WHERE id = $resource_id";

    echo "SQL Query: $sql<br>";

    if ($conn->query($sql) === TRUE) {
        echo "Resource updated successfully";
        header('Location: manage-resources.php'); // Redirect after successful update
        exit;
    } else {
        echo "Error updating resource: " . $conn->error;
    }

    $conn->close();
}
?>
