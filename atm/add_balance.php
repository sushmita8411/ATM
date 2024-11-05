<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

$userId = $_SESSION["user_id"]; // Get the user ID from the session
$pin = "";
$balanceToAdd = "";
$errors = array();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $pin = (int)$_POST["pin"];
    $balanceToAdd = (float)$_POST["balance"];

    // Validate inputs
    if (empty($pin) || empty($balanceToAdd)) {
        array_push($errors, "Both PIN and balance fields are required.");
    } else {
        // Retrieve the user's account number, balance, and pin
        $accountSql = "SELECT account_no, balance, pin FROM accounts WHERE user_id = ?";
        $accountStmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($accountStmt, $accountSql)) {
            mysqli_stmt_bind_param($accountStmt, "i", $userId);
            mysqli_stmt_execute($accountStmt);
            $result = mysqli_stmt_get_result($accountStmt);
            $account = mysqli_fetch_assoc($result);

            if ($account && $account['pin'] == $pin) {
                // Account exists and PIN matches, so update the balance
                $newBalance = $account["balance"] + $balanceToAdd;
                $updateSql = "UPDATE accounts SET balance = ? WHERE user_id = ? AND account_no = ?";
                $updateStmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($updateStmt, $updateSql)) {
                    mysqli_stmt_bind_param($updateStmt, "dii", $newBalance, $userId, $account['account_no']);
                    mysqli_stmt_execute($updateStmt);
                    echo "<div class='alert alert-success'>Balance updated successfully.</div>";
                }
            } else {
                array_push($errors, "Invalid PIN or account not found.");
            }
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
    <link rel="stylesheet" href="style1.css">

    <title>Add Balance</title>
</head>
<body>
    <div class="container">
        <h1>Add Balance</h1>
        <form action="add_balance.php" method="post">
            <div class="form-group">
                <input type="number" class="form-control" name="pin" placeholder="PIN (4 digits)" value="<?php echo htmlspecialchars($pin); ?>" required>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" class="form-control" name="balance" placeholder="Amount to Add" value="<?php echo htmlspecialchars($balanceToAdd); ?>" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Add Balance" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
