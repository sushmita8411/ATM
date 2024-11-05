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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .dashboard-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        .dashboard-header {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .dashboard-links {
            margin-top: 20px;
        }
        .dashboard-links a {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .btn-custom {
            width: 100%;
            padding: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?>!
        </div>
        <p>Select an option below:</p>

        <div class="dashboard-links">
            <a href="create_account.php" class="btn btn-primary btn-custom">Create Account</a>
            <a href="add_balance.php" class="btn btn-success btn-custom">Add Balance</a>
            <a href="tutorials.php" class="btn btn-info btn-custom">Watch Tutorials</a> <!-- New Button -->
            <a href="logout.php" class="btn btn-danger btn-custom">Log Out</a>
        </div>
    </div>
</body>
</html>
