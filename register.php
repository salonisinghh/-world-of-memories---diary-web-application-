<?php
session_start();
$conn = new mysqli("localhost", "root", "", "memories");

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$error_message = ""; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $error_message = " Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            $_SESSION['signup_success'] = true; // ✅ Store that signup was successful
            $_SESSION['user_name'] = $name;     // ✅ Store the user's name
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Unable to register!";
        }
        $stmt->close();
    }
    $check_email->close();

}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="register.css">
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
    <!-- Dark/Light Mode Toggle Button -->
    <button id="darkModeToggle">🌙 Dark Mode</button>
 
    <h2>Sign Up</h2>
    <!-- Display Error Message -->
 <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Account</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>

    <script>
        // Select the button and body
        const toggleButton = document.getElementById("darkModeToggle");
        const body = document.body;

        // Function to toggle Dark Mode
        function toggleDarkMode() {
            body.classList.toggle("dark-mode");

            // Check if dark mode is active
            let isDarkMode = body.classList.contains("dark-mode");
            localStorage.setItem("darkMode", isDarkMode);

            // Change button text and emoji
            if (isDarkMode) {
                toggleButton.textContent = "☀️ Light Mode";
            } else {
                toggleButton.textContent = "🌙 Dark Mode";
            }
        }

        // Event Listener for button
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
