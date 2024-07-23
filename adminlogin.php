<?php
// Hardcoded admin credentials
$adminUsername = "admin";
$adminPassword = "nderitu";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input username and password
    $inputUsername = $_POST["username"];
    $inputPassword = $_POST["password"];

    // Check if entered credentials match the admin credentials
    if ($inputUsername === $adminUsername && $inputPassword === $adminPassword) {
        // Redirect to admin.php if credentials are correct
        header("Location: admin1.php");
        exit;
    } else {
        // Redirect to adminerror.html if credentials are incorrect
        header("Location: adminloginerror.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>
<body>

<div class="login-container">
    <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Admin Login</h1>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
