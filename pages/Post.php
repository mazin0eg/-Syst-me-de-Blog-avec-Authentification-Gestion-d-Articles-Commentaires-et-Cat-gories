<?php
class Post {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllPosts() {
        try {
            $stmt = $this->conn->prepare("
                SELECT posts.*, users.username, 
                GROUP_CONCAT(tags.name SEPARATOR ', ') AS tag_names, 
                COUNT(likes.id) AS like_count
                FROM posts
                LEFT JOIN users ON posts.user_id = users.id
                LEFT JOIN post_tags ON posts.id = post_tags.post_id
                LEFT JOIN tags ON post_tags.tag_id = tags.id
                LEFT JOIN likes ON posts.id = likes.post_id
                GROUP BY posts.id
                ORDER BY posts.created_at DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving posts: " . $e->getMessage());
        }
    }
}
?>
