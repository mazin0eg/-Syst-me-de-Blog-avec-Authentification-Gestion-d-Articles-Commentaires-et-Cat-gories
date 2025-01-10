<?php
session_start();
include('config.php');
include('User.php');
include('Tag.php');
include('Post.php');
include('Like.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize User class and fetch the user by ID from session
$user = new User($conn);
$userId = $_SESSION['user_id'];
if ($userId === null) {
    echo "Error: User ID is not set.";
    exit();
}
$user->setId($userId); // Set the user ID based on session data

// Initialize other classes
$tag = new Tag($conn);
$post = new Post($conn);
$like = new Like($conn);

// Get all tags and posts
$tags = $tag->getAllTags();
$posts = $post->getAllPosts();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tweetButton'])) {
        $title = $_POST['tweettitle'];
        $content = $_POST['tweetContent'];
        $tags = isset($_POST['postTags']) ? $_POST['postTags'] : [];

        if (!empty($tags)) {
            $user->addPost($title, $content, $tags);
        } else {
            echo "Please select at least one tag.";
        }
    }

    // Handle like/unlike actions
    if (isset($_POST['likeButton'])) {
        $postId = $_POST['post_id'];
        $like->likePost($userId, $postId);
    } elseif (isset($_POST['unlikeButton'])) {
        $postId = $_POST['post_id'];
        $like->unlikePost($userId, $postId);
    }

    // Handle post deletion
    if (isset($_POST['deletePost'])) {
        $postId = $_POST['post_id'];
        $user->deletePost($postId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/home.css">
    <title>Home</title>
    <style>
        .like-button {
            background-color: #e0ffe0; /* Light green */
            color: #008000; /* Green */
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .like-button:hover {
            background-color: #c4ffc4;
        }

        .liked {
            background-color: #008000;
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <a href=""><img src="../image/twitter.png" alt=""></a>
    <ul>
        <li><a href="#"><img src="../image/home (1).png" alt="">Home</a></li>
        <li><a href="#"><img src="../image/search.png" alt="">Explore</a></li>
        <li><a href="#"><img src="../image/bell.png" alt="">Notifications</a></li>
        <li><a href="#"><img src="../image/users.png" alt="">Communities</a></li>
        <li><a href="logout.php"><img src="../image/sign-out-alt (1).png" alt="">Logout</a></li>
        <li><a href="#"><img src="../image/circle-ellipsis.png" alt="">More</a></li>     
    </ul>
</div>

<div class="main">
    <h2>Welcome, <?php echo htmlspecialchars($user->getUsername()); ?>!</h2>

    <!-- Tweet Box -->
    <div class="tweet-box">
        <form method="POST" action="home.php">
            <textarea name="tweettitle" rows="3" placeholder="What’s the topic?"></textarea>
            <textarea name="tweetContent" rows="3" placeholder="What’s happening?"></textarea>
            <label for="postTags">Select Tags:</label>
            <select name="postTags[]" id="postTags" multiple>
                <?php foreach ($tags as $tag) : ?>
                    <option value="<?php echo htmlspecialchars($tag['id']); ?>">
                        <?php echo htmlspecialchars($tag['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="tweetButton">Tweet</button>
        </form>
    </div>

    <!-- Posts -->
    <div class="tweets">
        <?php foreach ($posts as $post) : ?>
            <div class="tweet">
                <h3><?php echo htmlspecialchars($post['username']); ?> - <?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo htmlspecialchars($post['content']); ?></p>
                <p><strong>Tags:</strong> 
                    <?php 
                        $postTags = $post['tag_names'] ?? 'No tags';
                        echo htmlspecialchars($postTags); 
                    ?>
                </p>
                <small>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small>

                <p><strong>Likes:</strong> <?php echo htmlspecialchars($post['like_count']); ?></p>
                <form method="POST" action="home.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <?php if ($like->hasLiked($userId, $post['id'])) : ?>
                        <button type="submit" name="unlikeButton" class="like-button liked">❤️ Unlike</button>
                    <?php else : ?>
                        <button type="submit" name="likeButton" class="like-button">💚 Like</button>
                    <?php endif; ?>
                </form>

                <!-- Optional Delete Button -->
                <?php if ($post['user_id'] == $userId) : ?>
                    <form method="POST" action="home.php">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" name="deletePost">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
