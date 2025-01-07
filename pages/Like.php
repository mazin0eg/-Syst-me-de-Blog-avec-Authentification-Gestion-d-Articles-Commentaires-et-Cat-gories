<?php
class Like {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function likePost($userId, $postId) {
        $stmt = $this->conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $postId]);
    }

    public function unlikePost($userId, $postId) {
        $stmt = $this->conn->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
        return $stmt->execute([$userId, $postId]);
    }

    public function hasLiked($userId, $postId) {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$userId, $postId]);
        return $stmt->rowCount() > 0;
    }
}
?>
