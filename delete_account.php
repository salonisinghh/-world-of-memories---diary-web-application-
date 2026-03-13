<?php
session_start();
include 'db.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Delete all diary entries of the user
$sql1 = "DELETE FROM diary_entries WHERE user_id = (SELECT id FROM users WHERE email = ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $email);
$stmt1->execute();

// Delete user account
$sql2 = "DELETE FROM users WHERE email = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $email);

if ($stmt2->execute()) {
    session_destroy(); // End session
    header("Location:progressbar1.php?msg=Account deleted successfully.");
    exit();
} else {
    echo "Error deleting account.";
}

$conn->close();
?>
