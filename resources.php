<?php
include 'includes/db.php';
include 'includes/functions.php'; // Ensure functions.php is included for session checks

// Initialize search query and filter
$search_query = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$filter = isset($_GET['filter']) ? sanitizeInput($_GET['filter']) : 'all';

// Modify SQL query to filter based on the search query and filter
$sql_resources = "SELECT * FROM resources WHERE status='approved' AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR type LIKE '%$search_query%')";

if ($filter != 'all') {
    $sql_resources .= " AND type='$filter'";
}

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
            <form action="resources.php" method="GET" class="search-form">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search by title, description, or type">
                <button type="submit" class="button">Search</button>
            </form>

            <!-- Filter Chips -->
            <div class="filter-container">
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=all" class="chip <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=book" class="chip <?php echo $filter == 'book' ? 'active' : ''; ?>">Books</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=note" class="chip <?php echo $filter == 'note' ? 'active' : ''; ?>">Notes</a>
                <a href="resources.php?search=<?php echo urlencode($search_query); ?>&filter=question" class="chip <?php echo $filter == 'question' ? 'active' : ''; ?>">Questions</a>
            </div>

            <!-- Resources Grid -->
            <div class="resources-container">
                <?php if ($result_resources->num_rows > 0) { 
                    while ($resource = $result_resources->fetch_assoc()) { 
                        $thumbnail = 'uploads/thumbnail/' . htmlspecialchars($resource['thumbnail']);
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
                            <a href="<?php echo isUserLoggedIn() ? 'download.php?file=' . urlencode($file_path) . '&type=' . urlencode(strtolower($type)) : 'signin.php'; ?>" class="download-btn">
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
</body>
</html>
