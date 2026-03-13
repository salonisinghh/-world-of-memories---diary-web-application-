<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$original_id = $_POST['original_id'];
$title = $_POST['title'];
$date = $_POST['date'];
$content = $_POST['content'];
$imagePath = "";


// Upload image if given
// Check if new image is uploaded
if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
    $newImageName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $newImageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = $targetFile;
    }
} else {
    // Get original image path if no new image is uploaded
    $query = "SELECT image FROM diary_entries WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $original_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $imagePath = $row['image'];
    }
}

// Save edited entry in a new table
$sql = "INSERT INTO edited_entries (original_id, email, title, date, content, image) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $original_id, $email, $title, $date, $content, $imagePath);
$stmt->execute();

header("Location:progressbar3.php?");
exit();

?>