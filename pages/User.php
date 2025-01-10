<?php
class User {
    private $conn;
    private $id;
    private $username;
    private $email;
    private $password;
    private $created_at;
    private $role_id;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function populateUserData($user) {
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->created_at = $user['created_at'];
        $this->role_id = $user['role_id'];
    }

    public function register($email, $password1, $password2) {
        if ($password1 !== $password2) {
            throw new Exception("Passwords do not match.");
        }

        $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);
        $username = '@' . strstr($email, '@', true);

        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Email is already registered.");
        }

        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if (!$stmt->execute()) {
            throw new Exception("Registration failed: " . $stmt->errorInfo()[2]);
        }

        return $username;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->populateUserData($user);
            return true;
        }

        return false;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRole() {
        return $this->role_id == 1 ? 'admin' : 'user';
    }

    public function addPost($title, $content, $tags) {
        if ($this->id === null) {
            throw new Exception("User ID is not set.");
        }

        try {
            $stmt = $this->conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)");
            $stmt->bindParam(':user_id', $this->id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->execute();

            $postId = $this->conn->lastInsertId();
            foreach ($tags as $tagId) {
                $stmt = $this->conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)");
                $stmt->bindParam(':post_id', $postId);
                $stmt->bindParam(':tag_id', $tagId);
                $stmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error adding post: " . $e->getMessage());
        }
    }

    public function deletePost($postId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM posts WHERE id = :post_id AND user_id = :user_id");
            $stmt->bindParam(':post_id', $postId);
            $stmt->bindParam(':user_id', $this->id);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new Exception("Error deleting post: " . $e->getMessage());
        }
    }
}
?>
