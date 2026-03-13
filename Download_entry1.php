<?php
session_start();
require 'db.php';
require 'vendor/autoload.php'; // PHPWord library

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Validate entry ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$email = $_SESSION['email'];
$id = $_GET['id'];

// Fetch diary entry
$sql = "SELECT title, date, content, image FROM diary_entries WHERE id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Entry not found.";
    exit();
}

$row = $result->fetch_assoc();

// Create Word document
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Add title
$section->addTitle(htmlspecialchars($row['title']), 1);

// Add date
$section->addText("Date: " . $row['date']);
$section->addTextBreak(1);

// Add content
$section->addText(htmlspecialchars($row['content']));
$section->addTextBreak(1);

// Add image if exists
if (!empty($row['image']) && file_exists($row['image'])) {
    $section->addImage($row['image'], array('width' => 300, 'height' => 200));
}

// Save the Word document temporarily
$tempFile = tempnam(sys_get_temp_dir(), 'diary_entry') . '.docx';
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($tempFile);

// Force download
header("Content-Description: File Transfer");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['title']) . '.docx"');
header('Content-Length: ' . filesize($tempFile));
readfile($tempFile);

// Clean up
unlink($tempFile);
exit();
?>
