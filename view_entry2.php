<?php



session_start();
include"db.php";
// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Ensure a valid diary entry ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request.";
    exit();
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";

$id = $_GET['id'];
$email = $_SESSION['email'];

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diary Entry</title>
    <link rel="stylesheet" href="view.css">
    <script src="autolock.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
</head>
<body>
    <div class="navbar">
        <h2> W<img src="logo1.webp" style="height:50px;width:50px;border-radius: 50px;" alt="Memories">rld of Memories</h2>
       
        <div class="navbar-buttons">
        
        <button onclick="location.href='Download_entrynew.php?id=<?php echo $id; ?>'"><i class="fas fa-file-download" style="font-size:17px;color:white;margin-right:3px;">   </i> Download</button>
            <button class="btn-read" onclick="speakEntry()">🔊 Read Entry</button>
            <button class="btn-stop" onclick="stopSpeech()">⏹️ Stop</button>
            <button onclick="location.href='logout.php'" class="btn-logout">Logout</button>
            <button class="btn-toggle" onclick="toggleDarkMode()">
                <span id="theme-icon">🌙 Dark mode</span>
            </button>
        </div>
</div>
    
    <div class="container">
        <div class="entry">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <small><b>Date:</b> <?php echo $row['date']; ?></small>
            <p id="entryContent"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <?php if (!empty($row['image'])): ?>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="">
            <?php endif; ?>
        </div>
        <div class="buttons">
            <a href="dashboardb.php">Back to Dashboard</a>
        </div>
    </div>

    <script>
        let isDarkMode = false;
        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            isDarkMode = !isDarkMode;
            document.getElementById("theme-icon").innerText = isDarkMode ? "☀️ Light Mode" : "🌙 Dark Mode";
        }

        let speechSynthesisUtterance;
        function speakEntry() {
            let text = document.getElementById("entryContent").innerText;
            // Remove emojis using regex
    text = text.replace(/[\p{Emoji_Presentation}\p{Extended_Pictographic}]/gu, "");

            speechSynthesisUtterance = new SpeechSynthesisUtterance(text);

            speechSynthesisUtterance.rate = 0.6; // Slows down the speech (default is 1.0)
            window.speechSynthesis.speak(speechSynthesisUtterance);
        }

        function stopSpeech() {
            window.speechSynthesis.cancel();
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
