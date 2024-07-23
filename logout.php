<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page you prefer after a delay for visual effect
header("refresh:3;url=index.php"); // Redirect to the login page after 3 seconds
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logging Out</title>
<style>
/* Styles for loader */
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite; /* Spin animation */
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -60px; /* Center horizontally */
    margin-top: -60px; /* Center vertically */
}

/* Spin animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<div class="loader"></div> <!-- This div will display the loading animation -->
</body>
</html>
