<?php
session_start();
include 'db.php';

if (!isset($_SESSION['attachee_id'])) {
    header("Location: index.php");
    exit();
}

$id         = $_SESSION['attachee_id'];
$name       = $_POST['name'];
$email      = $_POST['email'];
$institution= $_POST['institution'];
$course     = $_POST['course'];

$sql = "UPDATE attachees 
        SET name='$name', email='$email', institution='$institution', course='$course' 
        WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    // ✅ Silent redirect without alert
    header("Location: dashboard.php?section=profileSection&updated=1");
    exit();
} else {
    echo "Error updating record: " . $conn->error;
}
?>
