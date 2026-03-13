<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
   
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Diary</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="autolock.js"></script>

</head>
<body>
    
    <nav class="navbar">
        <h1 class="nav-title">W<img src="logo1.webp" style="height:50px;width:50px;border-radius: 50px;" alt="Memories">rld of Memories</h1>
        
        
        <div class="user-menu">
            <span>Welcome, <?php echo htmlspecialchars($name); ?>!</span>
            <div class="dropdown">
                <div class="dropdown-item">
                    <a href="#"> Account Settings</a>
                    <div class="account-dropdown">
                        <a href="change_password.php">Change Password</a>
                        <a onclick="return confirm('Are you sure? This action cannot be undone.')"href="delete_account.php">
                            Delete Account</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="nav-buttons">
       
            <button class="b1" onclick="startSpeechRecognition()">🎤 Voice-to-Text</button>
            <button class="b1" onclick="location.href='progressbar.php'">📄 Saved Entries</button>
            <button class="b1" onclick="location.href='edited_fetch_progressbar.php'">📄 Edited Entries</button>
            <button id="darkModeToggle">🌙 Dark Mode</button>
            
        </div>
    </nav>

    <h2 class="center-heading">Create Your Memories Here</h2>

    <form class="diary-entry" action="save_entry.php" method="POST" enctype="multipart/form-data">
        <input id="title" type="text" name="title" placeholder="Title" required>
        <input id="entryDate" type="date" name="date" required>
        <textarea id="diaryText" name="content" placeholder="Write your diary..."></textarea>
        
        <!-- Emoji Picker -->
         
        <button class="b2" type="button" id="emojiButton">😀</button>
        <div id="emojiPicker" class="emoji-picker" style="display: none;"></div>

        <input class="b2" type="file" id="imageUpload" accept="image/*" name="uploadfile" style="display: none;">
        <button class="b2" type="button" onclick="document.getElementById('imageUpload').click();">📷 Upload</button>
        <img class="b2" id="previewImage" style="max-width: 200px; display: none;">
        <button class="b2" type="button" id="deleteImage" style="display: none;">🗑️ Remove Image</button>

        
        <button class="b2" type="reset">Reset</button>


        <button id="save_bttn" class="b2" type="submit">Save</button>

    </form>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById("darkModeToggle");
        const body = document.body;

        if (localStorage.getItem("darkMode") === "enabled") {
            body.classList.add("dark-mode");
            darkModeToggle.innerText = "☀️ Light Mode";
        }

        darkModeToggle.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            if (body.classList.contains("dark-mode")) {
                localStorage.setItem("darkMode", "enabled");
                darkModeToggle.innerText = "☀️ Light Mode";
            } else {
                localStorage.setItem("darkMode", "disabled");
                darkModeToggle.innerText = "🌙 Dark Mode";
            }
        });
       // Emoji Picker
      const emojiButton = document.getElementById("emojiButton");
      const emojiPicker = document.getElementById("emojiPicker");
      const titleInput = document.getElementById("title");
      const diaryText = document.getElementById("diaryText");

       const emojis = ["😊","🤭", "😢", "😔", "😞", "😕", "😟", "😖", "😭", "😩", "😃", "😄", "😁", "😆", "🙂", "😇", "😂", "❤️", "😍", "😭", "😎", "🤩", "🥰", "👍", "🔥", "🎉", "💖", "🎶", "🌞", "💯",
        "🙌", "🤗", "💡", "🌍", "🍕", "🎂", "☕", "🚀", "💪", "📝", "📚", "🎧", "🏆",
         "😅", "🤔", "😏", "😴", "🤯", "🤬", "😡", "🥺", "🤤", "😤", "😋", "🤫", "😕", "😬", "😶", "😑", "😒", "😜", "🤪", "😷", "🤧", "😚", "😙", "🤥", "🤠", "💔", "❤️‍🩹", "🤡",
      "🤝", "👏", "✌️", "🤲", "🙏", "👋", "👐", "✍️","🌸", "🌹", "🌷", "🌻", "🌺", "🌼","🗺️", "🌍", "🌎", "🌏"];

// Track active input field
     let activeInput = null;

    titleInput.addEventListener("focus", () => activeInput = titleInput);
    diaryText.addEventListener("focus", () => activeInput = diaryText);
     document.addEventListener("click", (event) => {
    if (!emojiPicker.contains(event.target) && event.target !== emojiButton) {
        emojiPicker.style.display = "none"; // Close picker when clicking outside
    }
    });

// Function to insert emoji at cursor position
     function insertEmoji(emoji) {
    if (!activeInput) return;

    let startPos = activeInput.selectionStart;
    let endPos = activeInput.selectionEnd;

    if (document.activeElement !== activeInput) activeInput.focus(); // Ensure input is focused

    // Insert emoji at cursor position
    let textBefore = activeInput.value.substring(0, startPos);
    let textAfter = activeInput.value.substring(endPos);
    activeInput.value = textBefore + emoji + textAfter;

    // Move cursor after inserted emoji
    let newCursorPosition = startPos + emoji.length;
    activeInput.setSelectionRange(newCursorPosition, newCursorPosition);

    // Hide emoji picker
    emojiPicker.style.display = "none";
}

// Generate emoji buttons
emojis.forEach(emoji => {
    let btn = document.createElement("button");
    btn.textContent = emoji;
    btn.classList.add("emoji-btn");
    btn.type = "button";
    btn.style.cursor = "pointer";
    btn.onclick = () => insertEmoji(emoji);
    emojiPicker.appendChild(btn);
});

// Toggle emoji picker visibility
emojiButton.addEventListener("click", () => {
    emojiPicker.style.display = emojiPicker.style.display === "none" ? "block" : "none";
});


        // Image Upload and Preview
        document.getElementById("imageUpload").addEventListener("change", function (event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.getElementById("previewImage");
                    let deleteBtn = document.getElementById("deleteImage");
                    img.src = e.target.result;
                    img.style.display = "block";
                    deleteBtn.style.display = "inline-block";
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById("deleteImage").addEventListener("click", function () {
            let img = document.getElementById("previewImage");
            let fileInput = document.getElementById("imageUpload");
            let deleteBtn = document.getElementById("deleteImage");
            img.src = "";
            img.style.display = "none";
            deleteBtn.style.display = "none";
            fileInput.value = "";
        });

        function startSpeechRecognition() {
            const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.onresult = (event) => {
                document.getElementById("diaryText").value += event.results[0][0].transcript;
            };
            recognition.start();
        }

   
   


    </script>

    <style>
        .emoji-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 10px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            position: absolute;
            width: 380px;
            left:4px;
            
        }
        .emoji-btn {
            background: none;
            border: none;
            font-size: 25px;
            cursor: pointer;
        }
        .emoji-btn:hover {
            background:black;
        }
    </style>
</body>
</html>
