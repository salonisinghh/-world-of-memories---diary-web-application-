<?php
session_start();
$conn = new mysqli("localhost", "root", "", "memories");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id,name,password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result( $user_id,$name, $hashed_password);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
           $_SESSION['name']=$name;
            $_SESSION['email'] = $email; // Store email in session for diary entries
            header("Location: dashboardb.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message="No account found! with the entered email";
    }
}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <!-- Dark/Light Mode Toggle Button -->
    <button id="darkModeToggle">🌙 Dark Mode</button>
    
    <?php
if (isset($_SESSION['signup_success']) && $_SESSION['signup_success'] === true) {
    $signupName = $_SESSION['user_name']; // Get name from session
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Welcome, $signupName! 🎉',
                text: 'Your account has been created successfully!',
                icon: 'success',
                confirmButtonText: 'Let\'s Login!',
                timer: 5000,
                timerProgressBar: true
            });
        });
    </script>
    ";
    unset($_SESSION['signup_success']); // Remove it after showing
    unset($_SESSION['user_name']);
}
?>


    <h2>Login</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
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
