<?php
include 'config.php'; // Database connection
include 'User.php'; // The User class

session_start(); // Start the session

// Redirect if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-login'])) {
    // Sanitize input
    $email = filter_var(trim($_POST['user-email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['user_password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        try {
            // Instantiate the User class and attempt login
            $user = new User($conn);
            if ($user->login($email, $password)) {
                // Set session variables
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_role'] = $user->getRole();

                // Redirect based on role
                if ($_SESSION['user_role'] === 'admin') {
                    header("Location: admin_home.php");
                } else {
                    header("Location: home.php");
                }
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $error = "An error occurred. Please try again later.";
            error_log($e->getMessage()); // Log the error
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/login.css">
  <title>Login</title>
</head>
<body>
  <div class="container">
    <div class="login form">
      <header>Login</header>

      <!-- Display error messages -->
      <?php if (!empty($error)): ?>
        <div class="error">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <input type="email" id="user-email" name="user-email" placeholder="Enter your email" required>
        <input type="password" id="user_password" name="user_password" placeholder="Enter your password" required>
        <input type="submit" class="button" name="user-login" value="Login">
      </form>
      <div class="signup">
        <span class="signup">Don't have an account?
          <a href="register.php">Signup</a>
        </span>
      </div>
    </div>
  </div>
</body>
</html>
