
<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";
$id = $_GET['id'];
$email = $_SESSION['email'];

$sql = "SELECT * FROM diary_entries WHERE id = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();

if (!$entry) {
    echo "Entry not found!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Diary Entry</title>
    <link rel="stylesheet" href="view.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="autolock.js"></script>
    <style>
        /* your existing CSS remains unchanged */
        body {
            font-family: Arial, sans-serif;
            background: #fdf6e3;
            margin: 0;
            padding: 0;
        }

        .navbar {
            padding: 5px 20px;
            height: 90px;
            background: linear-gradient(90deg, #3d0066, #ff0099);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar h2 {
            font-size: 32px;
            margin: 5px;
        }

        .navbar-buttons button {
            padding: 6px 10px;
            margin-left: 8px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(90deg, #ff0066, #ffcc00);
            color: white;
            cursor: pointer;
        }

        .container {
            max-width: 800px;
            margin: 100px auto 20px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 10px #aaa;
        }

        .editor-controls {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
        }

        .editor-controls button,
        .editor-controls select,
        .editor-controls label {
            padding: 6px 10px;
            border: none;
            border-radius: 8px;
            background: black;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .editor-controls button:hover,
        .editor-controls select:hover,
        .editor-controls label:hover{
transform:scale(1.1);
transition: transform 0.2s ease-in-out;

  }

        .editor-controls select option {
            background: white;
            color: black;
        }

        .editor-controls input[type="color"] {
            width: 22px;
            height: 22px;
            border: none;
            cursor: pointer;
            background: transparent;
        }
        .editor-controls label input[type="color"] {
    border-radius: 50%;
    border: 2px solid #333;
    padding: 0;
}

        #title-display {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #555;
            border-radius: 10px;
            padding: 8px;
            margin-bottom: 10px;
        }

        #editor {
            border: 2px solid #555;
            border-radius: 10px;
            padding: 10px;
            min-height: 150px;
            margin-bottom: 10px;
        }

        input[type="date"] {
            display: block;
            margin: 10px auto;
            padding: 8px;
            border: 2px solid #888;
            border-radius: 8px;
            font-size: 16px;
        }

        #image-preview {
            display: block;
            max-width: 100%;
            margin: 10px auto;
            border-radius: 10px;
            box-shadow: 0 0 5px #aaa;
        }
       


        .file-label {
            
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            padding: 8px 12px;
            background:linear-gradient(90deg, #3d0066, #ff0099);
            border-radius: 8px;
            text-align: center;
            margin-top: 10px;
            margin: 20px auto;
    display: block;  
            
        }
.file-label:hover{
    transform:scale(1.01);
    transition: transform 0.2s ease-in-out;
    background: linear-gradient(90deg, #ff0099, #3d0066);  
}
        .file-label i {
            font-size: 18px;
            margin-right: 5px;
        }

        input[type="file"] {
            display: none;
        }

        button[type="submit"],
        .reset-btn {
            margin: 10px 5px 0 0;
            padding: 10px 15px;
            background:linear-gradient(90deg, #3d0066, #ff0099);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            margin: 20px auto;
    display: block;  
            
        }

        .reset-btn{
            background: linear-gradient(90deg, #3d0066, #ff0099);
            font-size:14px;
            margin: 20px auto;
    display:block;  
    transition: transform 0.2s ease-in-out;
            
        }
        .reset-btn:hover{
            transform:scale(1.1);
            background: linear-gradient(90deg, #ff0099, #3d0066);  
        }
        .back-link {
            margin-top: 10px;
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            color: linear-gradient(90deg, #3d0066, #ff0099);
            font-weight: bold;
        }
        #deletePreviousImageBtn, #removeSelectedImageBtn{
            padding: 6px 10px;
            margin-left: 8px;
            border: none;
            border-radius:10px;
            background:black;
            color: white;
            font-weight:bold;
            cursor: pointer;
            margin: 20px auto;
    display: block;  
            
        }
        #deletePreviousImageBtn:hover, #removeSelectedImageBtn:hover{
            transform:scale(1.1);
    transition: transform 0.2s ease-in-out;
}
        
        /*user profile*/

.user-menu {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

/* Show the dropdown when hovering over the user menu */
.user-menu:hover .dropdown {
    display: block;
}

/* Dropdown Menu */
.dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    font-weight: bold;
    font-size: 15px;
    background:white;
    border-radius: 5px;
    z-index: 10;
    min-width: 160px;
}

/* Dropdown Items */
.dropdown a {
    display: block;
    padding: 7px;
    text-decoration: none;
    color:black;
    transition: background 0.2s;
}

/* Hover Effect */
.dropdown a:hover {
    border-radius: 5px;

    color:red;
}

/* Account Dropdown (Opens on Click) */
.account-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background:white;
    
    border-radius: 5px;
    min-width: 160px;
    z-index: 10;
}

/* When "Account Settings" is clicked, open the submenu */
.dropdown-item:focus-within .account-dropdown {
    display: block;
}
.account-dropdown a:active {
    transform: scale(0.95);
}
/*dark-mode*/
/* Dark Mode Styling */
.dark-mode body {
    background: #1a1a1a;
    color: white;
}





.dark-mode .container {
    background: rgba(30, 30, 30, 0.8);
    box-shadow: 0 0 12px rgba(255, 255, 255, 0.1);
    color: white;
}

.dark-mode .editor-controls button,
.dark-mode .editor-controls select,
.dark-mode .editor-controls label {
    background: #444;
    /*color: white;*/
    color:yellow;
    transition: transform 0.3s ease-in-out;
     /* smooth transition */
}
.dark-mode .editor-controls button:hover,
.dark-mode .editor-controls select:hover,
.dark-mode .editor-controls label:hover {
    transform: scale(1.1); /* slightly enlarges the element */
}

.dark-mode .editor-controls select option {
    background: #1a1a1a;
    color: white;
    
}

.dark-mode input[type="date"],
.dark-mode #title-display,
.dark-mode #editor {
    background: #2a2a2a;
    color: white;
    border: 1px solid #555;
}

.dark-mode #image-preview {
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
}


.dark-mode .back-link a {
   color: yellow;

}
.dark-mode .dropdown,
.dark-mode .account-dropdown {
    background: #2b2b2b;
    color: white;
    border: 1px solid #555;
}

.dark-mode .dropdown a,
.dark-mode .account-dropdown a {
    color: white;
}

.dark-mode .dropdown a:hover,
.dark-mode .account-dropdown a:hover {
    background: #444;
    color:yellow;
}

.dark-mode #deletePreviousImageBtn,
.dark-mode #removeSelectedImageBtn {
    background: #444;
    color: white;
}

.dark-mode hr {
    background-color: #444;
}
#savebtn:hover{
    background: linear-gradient(90deg, #ff0099, #3d0066);  
    transform:scale(1.1);
    transition: transform 0.2s ease-in-out;
}

        
    </style>
</head>
<body>

<div class="navbar">
    <h2>W<img src="logo1.webp" style="height:50px;width:50px;border-radius:50%;" alt="Memories">rld of Memories</h2>
    <div class="user-menu">
            <span>Welcome, <?php echo htmlspecialchars($name); ?>!</span>
            <div class="dropdown">
                <div class="dropdown-item">
                    <a href="#"> Account Settings</a>
                    <div class="account-dropdown">
                        <a href="change_password.php">Change Password</a>
                        <a onclick="return confirm('Are you sure? This action cannot be undone.')"href="delete_account.php">
                            Delete Account</a>
                        <!--<a href="logout.php">Logout</a>-->
                    </div>
                </div>
            </div>
        </div>
    <div class="navbar-buttons">
        
        <button class="b1" onclick="startSpeechRecognition()">🎤 Voice-to-Text</button>
        <button onclick="location.href='logout.php'">Logout</button>
        <button onclick="toggleDarkMode()"><span id="theme-icon">🌙 Dark mode</span></button>
    </div>
</div>

<div class="container">
    <h2 style="text-align:center;">Edit Diary Entry</h2>
    <hr style="height: 5px; background-color:brown; border: none; border-radius: 2px;">
    <form action="save_edited_entry.php" method="POST" enctype="multipart/form-data">
        <div class="editor-controls">
            <button type="button" onclick="format('bold')">BOLD</button>
            <button type="button" onclick="format('italic')">ITALIC</button>
            <button type="button" onclick="format('underline')">UNDERLINE</button>
            
            
            <label onclick="saveSelection()">TC
    <input type="color" onchange="applyStyle('color', this.value)">
</label>
<label onclick="saveSelection()">Bg C
    <input type="color" onchange="applyStyle('backgroundColor', this.value)">
</label>
            

            <select id="sizeSelect" onchange="formatFontSize(this.value)">
                <option disabled selected>Font Size</option>
                <option value="1">Very Small</option>
                <option value="2">Small</option>
                <option value="3">Normal</option>
                <option value="4">Large</option>
                <option value="5">Very Large</option>
            </select>
        </div>

        <input type="hidden" name="original_id" value="<?php echo $entry['id']; ?>">
        <div id="title-display" contenteditable="true"><?php echo htmlspecialchars($entry['title']); ?></div>
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($entry['title']); ?>">

        <input type="date" name="date" value="<?php echo $entry['date']; ?>" required>

        <div id="editor" contenteditable="true"><?php echo $entry['content']; ?></div>
        <input type="hidden" name="content" id="hiddenContent">

        <div id="image-section">

    <?php if (!empty($entry['image'])): ?>
        <!-- Show existing image -->
        <img id="image-preview" src="<?php echo $entry['image']; ?>" alt="Current Image"  onerror="this.src='default.jpg';" style="max-width: 100%; margin-bottom: 8px;">
        <!-- Show delete button below the image -->
        <button type="button" id="deletePreviousImageBtn" onclick="deletePreviousImage()">🗑️ Delete Image</button>
    <?php else: ?>
        <!-- No image present, hide preview initially -->
        <img id="image-preview" style="display: none;" alt="Image Preview">
    <?php endif; ?>
</div>

<!-- Show cut icon only when new image is selected -->
<div style="display: flex; justify-content: center;">
<button id="removeSelectedImageBtn" type="button" onclick="removeSelectedImage()" style="display:none;">✂️ Cancel Image</button>
    </div>

<!-- Always show camera icon to select image -->
 
<label class="file-label">
    <i class="fas fa-camera"></i> Select Image 
    <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)">
</label>

<!-- Hidden input to flag image deletion -->
<input type="hidden" name="delete_previous_image" id="deletePreviousImageInput" value="0">

<div>
            <button id="savebtn"  type="submit" onclick="copyContent()">Save Edited Entry</button>
            <button  type="button" class="reset-btn" onclick="resetEditor()">Reset</button>
    </div>
        </div>
    </form>

    <div class="back-link">
        <a href="dashboardb.php">← Back to Dashboard</a>
    </div>
</div>
<script>

function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle("dark-mode");

    const themeIcon = document.getElementById("theme-icon");
    if (body.classList.contains("dark-mode")) {
        themeIcon.textContent = "☀️ Light mode";
    } else {
        themeIcon.textContent = "🌙 Dark mode";
    }
}



let originalTitle = `<?php echo htmlspecialchars($entry['title'], ENT_QUOTES); ?>`;
let originalContent = `<?php echo htmlspecialchars($entry['content'], ENT_QUOTES); ?>`;
let originalImage = "<?php echo !empty($entry['image']) ? 'uploads/' . $entry['image'] : ''; ?>";

let activeEditable = null;
let savedSelection = null;

document.getElementById("title-display").addEventListener("focus", () => {
    activeEditable = "title";
});

document.getElementById("editor").addEventListener("focus", () => {
    activeEditable = "content";
});

function format(command, value = null) {
    document.execCommand("styleWithCSS", false, true);
    const target = (activeEditable === "title") ? document.getElementById("title-display") : document.getElementById("editor");
    target.focus();
    document.execCommand(command, false, value);
}

function saveSelection() {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        savedSelection = selection.getRangeAt(0);
    }
}

function restoreSelection() {
    if (savedSelection) {
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(savedSelection);
    }
}

function applyStyle(styleType, value) {
    restoreSelection(); // Restore saved selection
    const target = activeEditable === "title"
        ? document.getElementById("title-display")
        : document.getElementById("editor");

    target.focus();
    document.execCommand("styleWithCSS", false, true);

    if (styleType === "color") {
        document.execCommand("foreColor", false, value);
    } else if (styleType === "backgroundColor") {
        const cmd = document.queryCommandSupported("hiliteColor") ? "hiliteColor" : "backColor";
        document.execCommand(cmd, false, value);
    }
}

function formatFontSize(value) {
    document.execCommand("styleWithCSS", false, true);
    document.execCommand("fontSize", false, value);
}

function copyContent() {
    document.getElementById("hiddenContent").value = document.getElementById("editor").innerHTML;
    document.querySelector('input[name="title"]').value = document.getElementById("title-display").innerText;
}

function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const file = event.target.files[0];
    const cutBtn = document.getElementById('removeSelectedImageBtn');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            cutBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }
}

function removeSelectedImage() {
    const preview = document.getElementById('image-preview');
    const input = document.getElementById('imageInput');
    const cutBtn = document.getElementById('removeSelectedImageBtn');

    input.value = '';
    preview.src = '';
    preview.style.display = 'none';
    cutBtn.style.display = 'none';
}

// Called when user clicks "Delete Image" under old image
function deletePreviousImage() {
    const preview = document.getElementById('image-preview');
    const deleteBtn = document.getElementById('deletePreviousImageBtn');
    const deleteInput = document.getElementById('deletePreviousImageInput');

    preview.style.display = 'none';
    deleteBtn.style.display = 'none';
    deleteInput.value = '1'; // This will tell your PHP to remove image on save
}


function resetEditor() {
    if (confirm("Reset all changes?")) {
        document.getElementById("title-display").innerHTML = originalTitle;
        document.querySelector('input[name="title"]').value = originalTitle;
        document.getElementById("editor").innerHTML = originalContent;
        document.getElementById('imageInput').value = '';
        if (originalImage) {
            document.getElementById('image-preview').src = originalImage;
        }
    }
}
function startSpeechRecognition() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        const editor = document.getElementById("editor");
        editor.innerHTML += transcript + " ";
    };

    recognition.onerror = (event) => {
        alert("Speech recognition error: " + event.error);
    };

    recognition.start();
}

</script>


   </body>
</html>
