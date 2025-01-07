<?php
class Tag {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllTags() {
        $stmt = $this->conn->query("SELECT * FROM tags");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
