<?php
include 'config.php';
include 'User.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-register'])) {
    $email = trim($_POST['user-email']);
    $password1 = trim($_POST['user_password1']);
    $password2 = trim($_POST['user_password2']);

    try {
        $user = new User($conn);
        $username = $user->register($email, $password1, $password2);

        echo "Registration successful! Your username is: $username";
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/login.css">
    <title>Signup</title>
</head>
<body>
    <div class="container">
        <div class="registration form">
            <header>Signup</header>

            <!-- Display error messages -->
            <?php if (!empty($error)): ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" id="user-email" name="user-email" placeholder="Enter your email" required>
                <input type="password" id="user_password1" name="user_password1" placeholder="Enter your password" required>
                <input type="password" id="user_password2" name="user_password2" placeholder="Confirm your password" required>
                <input type="submit" class="button" name="user-register" value="Register">
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <a href="login.php">Login</a>
                </span>
            </div>
        </div>
    </div>
</body>
</html>
