<?php
session_start(); // Start the session

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the home page
header("Location: ../index.php"); // Adjust path based on your file structure
exit();
?>

