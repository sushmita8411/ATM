<?php
class User {
    private $conn;
    public $id;
    public $email;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($password, $user["password"])) {
            $this->id = $user["id"];
            $this->email = $user["email"];
            return true;
        }
        return false;
    }

    public static function isLoggedIn() {
        return isset($_SESSION["user_id"]);
    }
}
?>
