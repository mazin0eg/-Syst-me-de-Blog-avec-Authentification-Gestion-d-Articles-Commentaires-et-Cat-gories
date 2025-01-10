<?php
class Tag {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllTags() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tags");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving tags: " . $e->getMessage());
        }
    }
}
?>
