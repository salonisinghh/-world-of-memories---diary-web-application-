<?php
session_start();
require 'db.php';
require 'vendor/autoload.php'; // PHPWord library

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

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

// Fetch edited diary entry
$sql = "SELECT title, date, content, image FROM edited_entries WHERE id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Edited entry not found.";
    exit();
}

$row = $result->fetch_assoc();

// Create Word document
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Title
$section->addTitle(htmlspecialchars($row['title']), 1);

// Date
$section->addText("Date: " . $row['date']);
$section->addTextBreak(1);

// Convert and insert styled HTML content
Html::addHtml($section, $row['content'], false, false); // true = treat as external file (we don't want that)

// Add image if exists
if (!empty($row['image']) && file_exists($row['image'])) {
    $section->addTextBreak(1);
    $section->addImage($row['image'], array('width' => 300, 'height' => 200));
}

// Save temporarily and download
$tempFile = tempnam(sys_get_temp_dir(), 'edited_entry') . '.docx';
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($tempFile);

header("Content-Description: File Transfer");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['title']) . '_edited.docx"');
header('Content-Length: ' . filesize($tempFile));
readfile($tempFile);

// Clean up
unlink($tempFile);
exit();
?>
