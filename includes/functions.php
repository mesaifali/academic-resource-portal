<?php
// Include database connection
require_once 'db.php'; // Use require_once to ensure db.php is included only once

// Function to sanitize user input
function sanitizeInput($input) {
    global $conn;
    if (!$conn) {
        die("Database connection failed.");
    }
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

// Function to check if a user is logged in
function checkUserSession() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }
}

// Function to check if an admin is logged in
function checkAdminSession() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin-login.php");
        exit();
    }
}


// Function to get the current URL
function getCurrentURL() {
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}
?>

