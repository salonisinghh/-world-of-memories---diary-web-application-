<?php
session_start();
if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}

include "db.php";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "User";
$email = $_SESSION['email'];

$sql = "SELECT id, title, date FROM diary_entries WHERE email = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$entries = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Diary Entries</title>
    <script src="autolock.js"></script>
    <style>
     

     body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #f3e5f5, #e1bee7);
}
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    background: linear-gradient(90deg, #3d0066, #ff0099);
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    box-shadow: 0 5px 15px rgba(255, 102, 204, 0.5);
    margin-top:0px;
}
.navbar h1 {
    margin: 0;
    font-size: 32px;
}
.nav-right {
    display: flex;
    align-items: center;
}
.search-container {
    position: relative;
    display: flex;
    align-items: center;
    margin-right:15px;
}
.search-bar {
    padding: 8px 12px;
    border-radius: 20px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 16px;
    width: 250px;
}
.search-icon {
    position: absolute;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: gray;
}
.btn-logout {
    background: linear-gradient(90deg, #ff0066, #ffcc00);
color: white;
/* padding: 6px 10px;*/
padding:8px 12px;
padding-left:30px;
border-radius: 50px;
border: none;
cursor: pointer;
font-weight: bold;
transition: 0.3s;
text-decoration: none;
box-shadow: 0 5px 15px rgba(255, 102, 204, 0.5);
margin-right: 15px;
font-size:15px;
width:65px;

}
.btn-logout:hover{
    transform: scale(1.1);
    background: linear-gradient(90deg, #ffcc00, #ff0066);

}
.entry {
    background: white;
    border: 1px solid #ddd;
    padding: 15px;
    margin: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.btn {
    padding: 8px 12px;
    text-decoration: none;
    color: white;
    background: linear-gradient(90deg, #ff0066, #ffcc00);
    border-radius: 5px;
    margin-right: 10px;
    box-shadow: 0 5px 15px rgba(255, 102, 204, 0.5);
    cursor: pointer;
}
.btn:hover{
    transform: scale(1.1);
    background: linear-gradient(90deg, #ffcc00, #ff0066);
}
.dark-mode {
    background-color: #222;
    color: white;
}
.dark-mode .entry {
    background: #333;
    border-color: #444;
}
#darkModeToggle{
    background: linear-gradient(90deg, #ff0066, #ffcc00);
color: white;
padding: 8px 12px;
border-radius: 20px;
border: none;
cursor: pointer;
font-weight: bold;
transition: 0.3s;

box-shadow: 0 5px 15px rgba(255, 102, 204, 0.5);
margin-right: 15px;
}
#darkModeToggle:hover{
    transform: scale(1.1);
    background: linear-gradient(90deg, #ffcc00, #ff0066);}
/* Dark Mode */
.dark-mode {
background: #1a1a1a;
color: white;
}
/* Responsive Design */
@media (max-width: 1024px) {
    .search-bar {
        width: 200px;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
        padding: 15px;
    }
    .nav-right {
        justify-content: center;
        margin-top: 10px;
    }
    .search-bar {
        width: 180px;
    }
    .btn, .btn-logout, #darkModeToggle {
        font-size: 12px;
        padding: 6px 10px;
    }
}

@media (max-width: 480px) {
    .search-bar {
        width: 150px;
    }
    .search-container {
        flex-direction: column;
        align-items: center;
    }
    .search-icon {
        
        margin-top: 5px;
    }
    .entry {
        width:85%;
        
    }
    .btn, .btn-logout, #darkModeToggle {
        font-size: 10px;
        padding: 5px 8px;
    }
    
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



    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="navbar">
        <h1>W<img src="logo1.webp" style="height:50px;width:50px;border-radius:50px;" alt="Memories">rld of Memories</h1>
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
        <div class="nav-right">
           <div class="search-container">
                <input type="text" id="searchBar" class="search-bar" placeholder="Search memories...">
                <span class="search-icon" onclick="searchEntries()">🔍</span>
            </div>
            <a class="btn-logout" href="logout.php">Logout</a>
            <button id="darkModeToggle">🌙 Dark Mode</button>
        </div>
    </div>

    <h2 style="text-align:center;">Your Created Memories</h2>

    <div id="entriesContainer">
        <?php foreach ($entries as $row): ?>
            <div class="entry" data-title="<?php echo strtolower(htmlspecialchars($row['title'])); ?>">
                <h3><?php echo htmlspecialchars($row['title']); ?> (<?php echo $row['date']; ?>)</h3>
                <a class="btn" href="view_entry2.php?id=<?php echo $row['id']; ?>">Open</a>
                <a class="btn btn-delete" href="delete_entry.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                <a class="btn" href="edit_entry.php?id=<?php echo $row['id']; ?>">edit</a>           
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function searchEntries() {
            let input = document.getElementById("searchBar").value.toLowerCase();
            let entries = document.querySelectorAll(".entry");
            let container = document.getElementById("entriesContainer");
            let matched = [];

            entries.forEach(entry => {
                let title = entry.getAttribute("data-title");
                if (title.includes(input)) {
                    matched.push(entry);
                }
            });

            // Clear and re-add matched entries on top
            container.innerHTML = "";
            matched.forEach(entry => container.appendChild(entry));
            entries.forEach(entry => {
                if (!matched.includes(entry)) {
                    container.appendChild(entry);
                }
            });
        }
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

        
    </script>
    <script>
<?php if (isset($message)) { ?>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: '<?php echo $message; ?>',
  timer: 2000,
  showConfirmButton: false
});
<?php } ?>
</script>

</body>
</html>

<?php
$conn->close();
?>
