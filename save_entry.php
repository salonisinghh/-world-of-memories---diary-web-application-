<?php
session_start();


$servername = "localhost";  
$username = "root";         
$password = "";             
$database = "memories";   

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_SESSION['user_id'];
    $email = $_SESSION['email'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $content = $_POST['content'];
   
   $image = "uploads/";
   $filename = $_FILES["uploadfile"]["name"];
$tempname = $_FILES["uploadfile"]["tmp_name"];
$image = "uploads/".$filename;
move_uploaded_file($tempname,$image);
     

    $stmt = $conn->prepare("INSERT INTO diary_entries(user_id, email, title, date, content, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userid, $email, $title, $date, $content, $image);

    if ($stmt->execute()) {
    header("location: progressbar3.php");
    
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}


?>
