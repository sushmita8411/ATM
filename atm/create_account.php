<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once "database.php";

// Initialize variables for the form
$accountNo = "";
$pin = "";
$errors = array();

// Check if `user_id` is set in the session
if (!isset($_SESSION["user_id"])) {
    array_push($errors, "User session not set. Please log in again.");
} else {
    $userId = $_SESSION["user_id"]; // Set `user_id` for later use
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Sanitize input
    $accountNo = trim($_POST["account_no"]);
    $pin = trim($_POST["pin"]);

    // Validate inputs
    if (empty($accountNo) || empty($pin)) {
        array_push($errors, "All fields are required.");
    }
    if (!preg_match('/^\d{11}$/', $accountNo)) {
        array_push($errors, "Account number must be 11 digits.");
    }
    if (!preg_match('/^\d{4}$/', $pin)) {
        array_push($errors, "PIN must be 4 digits.");
    }

    // If no errors, proceed to insert the account
    if (count($errors) == 0) {
        // Check if account already exists
        $sql = "SELECT * FROM accounts WHERE account_no = ? AND user_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ii", $accountNo, $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $account = mysqli_fetch_assoc($result);

            if (!$account) {
                // Insert new account
                $insertSql = "INSERT INTO accounts (user_id, account_no, pin, balance) VALUES (?, ?, ?, 0.00)";
                $insertStmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($insertStmt, $insertSql)) {
                    mysqli_stmt_bind_param($insertStmt, "iii", $userId, $accountNo, $pin);
                    if (mysqli_stmt_execute($insertStmt)) {
                        echo "<div class='alert alert-success'>Account created successfully.</div>";
                    } else {
                        array_push($errors, "Failed to insert account: " . mysqli_error($conn));
                    }
                } else {
                    array_push($errors, "Failed to prepare insert statement: " . mysqli_error($conn));
                }
            } else {
                array_push($errors, "Account already exists.");
            }
        } else {
            array_push($errors, "Failed to prepare select statement: " . mysqli_error($conn));
        }
    }

    // Display errors
    foreach ($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style1.css">

    <title>Create Account</title>

</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <form action="create_account.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="account_no" placeholder="Account No (11 digits)" value="<?php echo htmlspecialchars($accountNo); ?>" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="pin" placeholder="PIN (4 digits)" value="<?php echo htmlspecialchars($pin); ?>" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Create Account" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
