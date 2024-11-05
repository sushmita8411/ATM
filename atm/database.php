<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";

// Establishing the connection
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Check if connection was successful
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: Enable error reporting in case of SQL issues during debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
