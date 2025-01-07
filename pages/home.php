<?php
session_start();
include('config.php');
include('User.php');
include('Tag.php');
include('Post.php');
include('Like.php');

// Ensure user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Get the user ID from the email
$user = new User($conn, $_SESSION['user_email'], 0); // Temporarily create the user to fetch user ID
$userId = $user->getUserIdByEmail($_SESSION['user_email']); // Retrieve the user ID using the email

// Initialize the User class correctly with all parameters
$user = new User($conn, $_SESSION['user_email'], $userId); // Initialize User class with all arguments

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
        $tags = $_POST['postTags'];
        $post->createPost($userId, $title, $content, $tags);
    } elseif (isset($_POST['deletePost'])) {
        $post->deletePost($_POST['post_id'], $userId);
    } elseif (isset($_POST['likeButton'])) {
        $like->likePost($userId, $_POST['post_id']);
    } elseif (isset($_POST['unlikeButton'])) {
        $like->unlikePost($userId, $_POST['post_id']);
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
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</h2>

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
                <p><strong>#</strong><?php echo htmlspecialchars($post['tag_names'] ?? 'No tags'); ?></p>
                <small>Posted on: <?php echo htmlspecialchars($post['created_at']); ?></small>
                
                <p><strong>Likes:</strong> <?php echo htmlspecialchars($post['like_count']); ?></p>
                <form method="POST" action="home.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <?php if ($like->hasLiked($userId, $post['id'])) : ?>
                        <button type="submit" name="unlikeButton">Unlike</button>
                    <?php else : ?>
                        <button type="submit" name="likeButton">Like</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
