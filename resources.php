<?php
include 'includes/db.php';
include 'includes/functions.php'; // Ensure functions.php is included for session checks

// Initialize search query
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Modify SQL query to filter based on the search query
$sql_resources = "SELECT * FROM resources WHERE status='approved' AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR type LIKE '%$search_query%')";
$result_resources = $conn->query($sql_resources);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Resources - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <section class="resources-section">
            <h2>All Resources</h2>

            <!-- Search Form -->
            <form action="resource.php" method="GET" class="search-form">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title, description, or type">
                <button type="submit" class="button">Search</button>
            </form>

            <!-- Resources Grid -->
            <div class="resources-container">
                <?php if ($result_resources->num_rows > 0) { 
                    while ($resource = $result_resources->fetch_assoc()) { 
                        $thumbnail = htmlspecialchars($resource['thumbnail']);
                        $title = htmlspecialchars($resource['title']);
                        $type = ucfirst(htmlspecialchars($resource['type']));
                        $description = htmlspecialchars($resource['description']);
                        $file_path = htmlspecialchars($resource['file_path']);
                ?>
                    <div class="resource-card">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="resource-thumbnail">
                        <div class="resource-info">
                            <h3><?php echo $title; ?></h3>
                            <p class="resource-type">Type: <?php echo $type; ?></p>
                            <p><?php echo $description; ?></p>
                            <a href="<?php echo isUserLoggedIn() ? 'uploads/' . $file_path : 'signin.php'; ?>" class="download-btn">
                                <?php echo isUserLoggedIn() ? 'Download' : 'Sign In to Download'; ?>
                            </a>
                        </div>
                    </div>
                <?php } 
                } else { ?>
                    <p>No resources found matching your search.</p>
                <?php } ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Academic Resource Portal. All Rights Reserved.</p>
    </footer>
</body>
</html>

