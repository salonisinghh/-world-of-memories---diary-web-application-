<?php


session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saving Entry...</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg,rgb(191, 114, 203), #e1bee7);
            font-family: Arial, sans-serif;
        }
        .progress-container {
            width: 50%;
            background-color: #ccc;
            border-radius: 25px;
            overflow: hidden;
            height: 30px;
        }
        .progress-bar {
            width: 0%;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Fetching Your Memories...</h2>
<div class="progress-container">
    <div class="progress-bar" id="progressBar">0%</div>
</div>

<script>
    let progress = 0;
    let interval = setInterval(() => {
        progress += 10;
        document.getElementById("progressBar").style.width = progress + "%";
        document.getElementById("progressBar").innerText = progress + "%";

        if (progress >= 100) {
            clearInterval(interval);
            window.location.href = "diarydashboard.php"; // Redirect after progress completes
        }
    }, 300);
</script>

</body>
</html>
