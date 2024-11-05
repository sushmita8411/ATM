<?php
class Account {
    private $conn;
    public $userId;
    public $accountNo;
    public $balance;

    public function __construct($conn, $userId) {
        $this->conn = $conn;
        $this->userId = $userId;
    }

    public function createAccount($accountNo, $pin) {
        $sql = "INSERT INTO accounts (user_id, account_no, pin, balance) VALUES (?, ?, ?, 0.00)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $this->userId, $accountNo, $pin);
        return $stmt->execute();
    }

    public function addBalance($accountNo, $pin, $amount) {
        $sql = "SELECT * FROM accounts WHERE user_id = ? AND account_no = ? AND pin = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $this->userId, $accountNo, $pin);
        $stmt->execute();
        $account = $stmt->get_result()->fetch_assoc();
        
        if ($account) {
            $newBalance = $account["balance"] + $amount;
            $updateSql = "UPDATE accounts SET balance = ? WHERE user_id = ? AND account_no = ?";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bind_param("dii", $newBalance, $this->userId, $accountNo);
            return $updateStmt->execute();
        }
        return false;
    }
}
?>
