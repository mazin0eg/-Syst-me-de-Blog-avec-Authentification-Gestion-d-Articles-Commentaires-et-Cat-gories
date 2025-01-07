<?php
include 'config.php'; // Include the database connection

session_start(); // Start the session

// Redirect if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-login'])) {
    // Sanitize and validate input
    $email = filter_var(trim($_POST['user-email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['user_password']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        try {
            // Prepare a secure SQL query using PDO
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = ($user['role_id'] == 1) ? 'admin' : 'user';

                    // Redirect based on the user role
                    if ($_SESSION['user_role'] === 'admin') {
                        header("Location: admin_home.php");
                    } else {
                        header("Location: home.php");
                    }
                    exit();
                } else {
                    // Invalid password
                    $error = "Incorrect password. Please try again.";
                }
            } else {
                // Email not found
                $error = "No account found with this email.";
            }
        } catch (PDOException $e) {
            // Handle database connection errors
            $error = "An error occurred. Please try again later.";
            error_log($e->getMessage()); // Log the error for debugging
        }
    } else {
        $error = "Invalid email or password. Please try again.";
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
