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

// Delete from the edited_entries table where id and email match
$sql = "DELETE FROM edited_entries WHERE id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $email);

if ($stmt->execute()) {
    header("Location: edited_entrydashboard.php?msg=Edited entry deleted successfully");
    exit();
} else {
    echo "Error deleting edited entry.";
}

$conn->close();
?>
