<?php
class User {
    private $conn;
    private $email;
    private $userId;

    // Constructor expects three parameters
    public function __construct($conn, $email, $userId) {
        $this->conn = $conn;
        $this->email = $email;
        $this->userId = $userId;
    }

    // Example method to get user ID based on email
    public function getUserIdByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['id'];
    }
}
?>
