<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorials</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        .tutorial-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white for readability */
            border-radius: 8px;
        }
        .tutorial-header {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
        }
        .tutorial-item {
            margin-bottom: 30px;
            text-align: center;
        }
        iframe {
            width: 100%;
            height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="tutorial-container">
        <div class="tutorial-header">Video Tutorials</div>

        <div class="tutorial-item">
            <iframe src="https://www.youtube.com/embed/NwcO2O_Gv9M" title="Tutorial 1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p>Tutorial 1</p>
        </div>

        <div class="tutorial-item">
            <iframe src="https://www.youtube.com/embed/GAt3AgWqwV4" title="Tutorial 2" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p>Tutorial 2</p>
        </div>

        <div class="tutorial-item">
            <iframe src="https://www.youtube.com/embed/aOEGuRU68hc" title="Tutorial 3" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p>Tutorial 3</p>
        </div>

        <a href="index.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>
</body>
</html>
