

<?php
// Disable caching to prevent back navigation after logout
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies


session_start();
include 'db.php'; // Include database connection

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


$email = $_SESSION['email']; // Get logged-in user's email
$error_message = ""; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate new password and confirm password
    if ($new_password !== $confirm_password) {
        $error_message =  "New passwords do not match.";
       // exit();
    }
else{
    // Fetch user's current hashed password
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();
    if (!$stored_password) {
        $error_message = "User not found.";
    }
    // Verify old password
    elseif (!password_verify($old_password, $stored_password)) {
        $error_message = "Incorrect old password.";
        //exit();
    }
else{
    // Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_hashed_password, $email);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location:progressbar4.php"); // Redirect after success
        exit();
    } else {
         $error_message ="Error updating password.";
    }
}
}
}
   // $stmt->close();


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            font-size:20px;
        }
    </style>
</head>
<body>

    <button id="darkModeToggle">🌙 Dark Mode</button>

    <h2>Change Password</h2>
    
    <?php if (!empty($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <form method="POST" action="change_password.php">
        <label>Enter Old Password:</label>
        <input type="password" name="old_password" required>
        
        <label>Enter New Password:</label>
        <input type="password" name="new_password" required>
        
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>
        
        <button type="submit">Change Password</button>
    </form>
    

    <script>
        // Select the toggle button and body
        const toggleButton = document.getElementById("darkModeToggle");
        const body = document.body;

        // Function to toggle dark mode
        function toggleDarkMode() {
            body.classList.toggle("dark-mode");

            // Save the mode in localStorage
            let isDarkMode = body.classList.contains("dark-mode");
            localStorage.setItem("darkMode", isDarkMode);

            // Change button text & emoji
            toggleButton.textContent = isDarkMode ? "☀️ Light Mode" : "🌙 Dark Mode";
        }

        // Event listener for toggle button
        toggleButton.addEventListener("click", toggleDarkMode);

        // Keep Dark Mode Active on Page Reload
        window.onload = function () {
            if (localStorage.getItem("darkMode") === "true") {
                body.classList.add("dark-mode");
                toggleButton.textContent = "☀️ Light Mode";
            }
        };
    </script>
</body>
</html>


