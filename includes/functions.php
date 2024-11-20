<?php
require_once 'db.php'; 
function sanitizeInput($input) {
    global $conn;
    if (!$conn) {
        die("Database connection failed.");
    }
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}

function checkUserSession() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }
}

function checkAdminSession() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin-login.php");
        exit();
    }
}


function getCurrentURL() {
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}
?>

