<?php
session_start();
include 'includes/db.php';
include 'includes/functions.php';

// Debugging: Output the raw inputs
echo 'Raw file input: ' . htmlspecialchars($_GET['file']) . '<br>';
echo 'Raw type input: ' . htmlspecialchars($_GET['type']) . '<br>';

if (isset($_GET['file']) && isset($_GET['type'])) {
    $file = basename($_GET['file']);
    $type = strtolower($_GET['type']); // Convert type to lowercase
    
    // Construct the file path
    $file_path = 'uploads/' . $type . '/' . $file;
    $real_path = realpath($file_path); // Get the actual file system path
    
    // Debugging: Output the constructed paths
    echo 'Requested file: ' . htmlspecialchars($file) . '<br>';
    echo 'File type: ' . htmlspecialchars($type) . '<br>';
    echo 'Constructed full path: ' . htmlspecialchars($file_path) . '<br>';
    echo 'Real path: ' . htmlspecialchars($real_path) . '<br>';

    // Check if the file exists
    if ($real_path && file_exists($real_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($real_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($real_path));
        readfile($real_path);
        exit;
    } else {
        echo 'File not found at: ' . htmlspecialchars($file_path);
    }
} else {
    echo 'No file or type specified.';
}
