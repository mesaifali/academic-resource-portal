<?php
$servername = "local";
$username = "root";
$password = "";
$dbname = "academic_resource_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
