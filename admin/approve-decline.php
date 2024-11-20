<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin-login.php");
    exit();
}
include '../includes/db.php';
if (isset($_GET['approve'])) {
    $resource_id = $_GET['approve'];
    $sql_approve = "UPDATE resources SET status='approved' WHERE id='$resource_id'";
    $conn->query($sql_approve);
    header("Location: manage-approve-resources.php");
}
if (isset($_GET['decline'])) {
    $resource_id = $_GET['decline'];
    $sql_decline = "UPDATE resources SET status='declined' WHERE id='$resource_id'";
    $conn->query($sql_decline);
    header("Location: manage-approve-resources.php");
}
$sql_pending_resources = "SELECT * FROM resources WHERE status='pending'";
$result_pending_resources = $conn->query($sql_pending_resources);
$conn->close();
?>