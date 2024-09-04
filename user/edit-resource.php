<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

// Initialize resource data
$resource_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];
$resource = null;

if ($resource_id > 0) {
    // Modified query to include a check for the user_id
    $sql = "SELECT * FROM resources WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $resource_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $resource = $result->fetch_assoc();
    } else {
        // Redirect to error page if the resource doesn't belong to the user
        header("Location: http://noteshare.free.nf/error.php");
        exit();
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']);
    $file_path = htmlspecialchars($_POST['file_path']);
    $thumbnail = htmlspecialchars($_POST['thumbnail']);

    // Update resource in the database
    $sql = "UPDATE resources SET title=?, description=?, type=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $type, $resource_id, $user_id);
    
    if ($stmt->execute()) {
        echo 'Resource updated successfully.';
    } else {
        echo 'Error updating resource: ' . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
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
                    <input type="hidden" name="file_path" value="<?php echo htmlspecialchars($resource['file_path']); ?>">
                    <input type="hidden" name="thumbnail" value="<?php echo htmlspecialchars($resource['thumbnail']); ?>">
                    <button type="submit" class="button">Update Resource</button>
                    <a class="button-back" onclick="history.back()" href="">Back to Manage Resource</a>
                </form>
            <?php } else { ?>
                <p>Resource not found or you don't have permission to edit it.</p>
            <?php } ?>
        </section>
    </main>
</body>
</html>