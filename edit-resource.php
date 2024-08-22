<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';
include 'includes/functions.php';

// Initialize resource data
$resource_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$resource = null;

if ($resource_id > 0) {
    $sql = "SELECT * FROM resources WHERE id = $resource_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $resource = $result->fetch_assoc();
    } else {
        echo 'Resource not found.';
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']);
    $file_path = htmlspecialchars($_POST['file_path']); // assuming the file_path is not changed
    $thumbnail = htmlspecialchars($_POST['thumbnail']); // assuming the thumbnail is not changed

    // Update resource in the database
    $sql = "UPDATE resources SET title='$title', description='$description', type='$type' WHERE id=$resource_id";
    
    if ($conn->query($sql) === TRUE) {
        echo 'Resource updated successfully.';
    } else {
        echo 'Error updating resource: ' . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Edit Resource - Academic Resource Portal</title>
</head>
<body>
    <main>
        <section class="form-container">
            <h2>Edit Resource</h2>
            <?php if ($resource) { ?>
                <form action="edit-resource.php?id=<?php echo $resource_id; ?>" method="POST">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($resource['title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="book" <?php echo $resource['type'] == 'book' ? 'selected' : ''; ?>>Book</option>
                            <option value="notes" <?php echo $resource['type'] == 'notes' ? 'selected' : ''; ?>>Notes</option>
                            <option value="question" <?php echo $resource['type'] == 'question' ? 'selected' : ''; ?>>Question</option>
                        </select>
                    </div>

                    <!-- Assuming file_path and thumbnail fields are not editable in this example -->
                    <input type="hidden" name="file_path" value="<?php echo htmlspecialchars($resource['file_path']); ?>">
                    <input type="hidden" name="thumbnail" value="<?php echo htmlspecialchars($resource['thumbnail']); ?>">

                    <button type="submit" class="button">Update Resource</button>
                    <a class="button-back"onclick="history.back()" href="">Back to Manage Resource</a>
                </form>
            <?php } else { ?>
                <p>Resource not found.</p>
            <?php } ?>
        </section>
    </main>
</body>
</html>
