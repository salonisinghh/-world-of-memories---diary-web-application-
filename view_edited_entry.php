<?php
session_start();
include "db.php";

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Check for valid edited entry ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";
$id = $_GET['id'];
$email = $_SESSION['email'];

// Fetch from edited_entries table
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edited Diary Entry</title>
    <link rel="stylesheet" href="view.css">
    <script src="autolock.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body >
    <div class="navbar">
        <h2> W<img src="logo1.webp" style="height:50px;width:50px;border-radius: 50px;" alt="Memories">rld of Memories</h2>
        <div class="navbar-buttons">
            <button onclick="location.href='Download_edited_entry.php?id=<?php echo $id; ?>'"><i class="fas fa-file-download"></i> Download</button>
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
            <h2><?php echo ($row['title']); ?></h2>
            <small><b>Date:</b> <?php echo $row['date']; ?></small>
            <p id="entryContent"><?php echo nl2br(($row['content'])); ?></p>
            <?php if (!empty($row['image'])): ?>
                <img src="<?php echo htmlspecialchars ($row['image']); ?>" alt="">
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
        let synth = window.speechSynthesis;
let utterance;

function removeEmojis(text) {
    return text.replace(/[\u{1F600}-\u{1F6FF}|\u{1F300}-\u{1F5FF}|\u{1F700}-\u{1F77F}|\u{1F780}-\u{1F7FF}|\u{1F800}-\u{1F8FF}|\u{1F900}-\u{1F9FF}|\u{1FA00}-\u{1FA6F}|\u{1FA70}-\u{1FAFF}|\u{2600}-\u{26FF}|\u{2700}-\u{27BF}]/gu, '');
}

function speakEntry() {
    const rawContent = document.getElementById("entryContent").innerText;
    const content = removeEmojis(rawContent);

    if (synth.speaking) {
        synth.cancel();
    }

    utterance = new SpeechSynthesisUtterance(content);
    utterance.lang = 'en-US';
    utterance.rate = 0.6;
    utterance.pitch = 1;

    synth.speak(utterance);
}

function stopSpeech() {
    if (synth.speaking) {
        synth.cancel();
    }
}

        
    
    </script>
</body>
</html>
<?php $conn->close(); ?>
