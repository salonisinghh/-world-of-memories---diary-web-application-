<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$id = $_GET['id'];
$email = $_SESSION['email'];

$sql = "DELETE FROM diary_entries WHERE id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $email);

if ($stmt->execute()) {
    header("Location: diarydashboard.php?msg=Entry deleted successfully");
    exit();
} else {
    echo "Error deleting entry.";
}

$conn->close();
?>
