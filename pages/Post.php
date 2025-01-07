<?php
class Post {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createPost($userId, $title, $content, $tags) {
        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $title, $content]);
            $postId = $this->conn->lastInsertId();

            $stmt = $this->conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
            foreach ($tags as $tagId) {
                $stmt->execute([$postId, $tagId]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getAllPosts() {
        $stmt = $this->conn->query("
            SELECT posts.*, users.username, 
                   GROUP_CONCAT(tags.name SEPARATOR ', ') AS tag_names, 
                   (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            LEFT JOIN post_tags ON posts.id = post_tags.post_id
            LEFT JOIN tags ON post_tags.tag_id = tags.id
            GROUP BY posts.id
            ORDER BY posts.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost($postId, $userId) {
        $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        return $stmt->execute([$postId, $userId]);
    }
}
?>
