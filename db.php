<?php
$servername = "localhost";  // If hosted remotely, change this
$username = "root";         // Default for XAMPP/MAMP
$password = "";             // Keep empty for XAMPP default settings
$database = "memories";    // Database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>