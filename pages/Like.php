<?php
class Like {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function hasLiked($userId, $postId) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND post_id = :post_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':post_id', $postId);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error checking like status: " . $e->getMessage());
        }
    }

    public function likePost($userId, $postId) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':post_id', $postId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error liking post: " . $e->getMessage());
        }
    }

    public function unlikePost($userId, $postId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':post_id', $postId);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error unliking post: " . $e->getMessage());
        }
    }
}
?>
