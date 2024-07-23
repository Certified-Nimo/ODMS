<?php
require 'connection.php';
require 'log_event.php'; // Include the logging function

if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a new instance of the Database class and connect
    $database = new Database();
    $conn = $database->connect();

    if ($conn) {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT u.userID, u.password, ud.user_id AS profileUserID FROM users u LEFT JOIN user_details ud ON u.userID = ud.user_id WHERE u.email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->execute([$email]);
            $stmt->bind_result($userID, $hashedPassword, $profileUserID);
          if ($stmt->fetch()) {
            $user = [
           'userID' => $userID,
           'password' => $hashedPassword,
           'profileUserID' => $profileUserID
          ];
          }


            if ($user && password_verify($password, $user['password'])) {
                // Log the successful login event
                logEvent($email, 'userSessionCreate', 'POST', 'login.php', 200);

                // Start the session
                session_start();

                // Set the session variables
                $_SESSION['user_id'] = $user['userID'];

                // Check if the user has a profile
                if ($user['profileUserID']) {
                    // Redirect to the user profile
                    header("Location: userProfile.php");
                } else {
                    // Redirect to the profile creation form
                    header("Location: userDetails.php");
                }
                exit;
            } else {
                // Log the failed login attempt
                logEvent($email, 'userSessionCreateFailed', 'POST', 'login.php', 401);
                echo "<script>alert('Incorrect email or password');</script>";
            }
        } else {
            // Log the prepared statement error
            logEvent($email, 'preparedStatementError', 'POST', 'login.php', 500);
            echo "<script>alert('Error in prepared statement');</script>";
        }
    } else {
        echo 'Connection error: could not connect to the database.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="admin.css">
    <title>Login</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; 
    background: linear-gradient(to top, rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%), url(img/1.jpg);">
    
    <div class="login-container">
        <div class="image-container">
            <img src="img/logo1.png" alt="Login Image">
        </div>
        <div class="form-container">
            <div class="login-form">
                <h2>Login</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit">Login</button>
                    </div>
                </form>
                <div class="acc">
                    Don't have an account? <a href="register.php">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
