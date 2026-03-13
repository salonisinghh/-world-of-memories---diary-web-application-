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
            background: linear-gradient(135deg, rgb(191, 114, 203), #e1bee7);
            font-family: Arial, sans-serif;
            overflow: hidden;
        }
        .progress-container {
            position: relative;
            width: 130px;
            height: 130px;
        }
        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 10px solid lightgray;
            position: absolute;
            top: 0;
            left: 0;
            clip-path: circle();
            background: white; /* Added white background */
            z-index: 1;
        }
        .progress-fill {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            clip-path: circle();
            background: conic-gradient(#4caf50 0deg, lightgray 0deg);
            transform: rotate(-90deg);
            transition: all 0.3s;
            z-index: 2;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: bold;
            z-index: 3;
        }
        .checkmark {
            display: none;
            font-size: 50px;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #4caf50;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            
            align-items: center;
            justify-content: center;
            z-index: 4;
            
            
        }
        .success-text {
            display: none;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #4caf50;
        }
        .ribbons {
            position: absolute;
            top: -50px;
            width: 10px;
            height: 30px;
            background: red;
            opacity: 0.8;
            animation: fall 2s linear forwards;
        }
        @keyframes fall {
            0% { top: -50px; opacity: 1; }
            100% { top: 100vh; opacity: 0; }
        }
    </style>
</head>
<body>

<div class="progress-container">
    <div class="progress-circle"></div>
    <div class="progress-fill" id="progressFill"></div>
    <div class="progress-text" id="progressText">0%</div>
    <div class="checkmark" id="checkmark">✔</div>
</div>
<div class="success-text" id="successText">Saved Successfully!</div>

<script>
    let progress = 0;
    let interval = setInterval(() => {
        progress += 10;
        let degrees = (progress / 100) * 360;
        document.getElementById("progressFill").style.background = `conic-gradient(#4caf50 ${degrees}deg, lightgray ${degrees}deg)`;
        document.getElementById("progressText").innerText = progress + "%";
      
        if (progress >= 100) {
        clearInterval(interval);
        
        //  Ensure the progress animation completes first
        setTimeout(() => {
            document.getElementById("progressText").style.display = "none";
            document.getElementById("checkmark").style.display = "flex"; // ✅ Now appears correctly
            document.getElementById("successText").style.display = "block";
            startRibbons();
        }, 200);  // Slight delay to ensure smooth animation

        setTimeout(() => {
            window.location.href = "dashboardb.php";
        }, 3000);  // Redirect after 3 seconds
    }
}, 300);
        
    function createRibbon() {
        let ribbon = document.createElement("div");
        ribbon.classList.add("ribbons");
        ribbon.style.left = Math.random() * 100 + "vw";
        ribbon.style.background = `hsl(${Math.random() * 360}, 100%, 50%)`;
        ribbon.style.animationDuration = Math.random() * 2 + 1 + "s";
        document.body.appendChild(ribbon);
        setTimeout(() => ribbon.remove(), 2000);
    }

    function startRibbons() {
        let ribbonInterval = setInterval(() => {
            for (let i = 0; i < 5; i++) { // Generate multiple ribbons at a time
                createRibbon();
            }
        }, 300); // Create ribbons every 300ms

        setTimeout(() => clearInterval(ribbonInterval), 3000); // Stop after 3 seconds
    }
</script>

</body>
</html>
