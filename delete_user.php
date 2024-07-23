<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Delete user from the database
    $query = "DELETE FROM users WHERE userID = $userID";
    $result = mysqli_query($conn, $query);

    // Redirect back to displayusers.php after deletion
    header("Location: displayusers.php");
    exit();
} else {
    echo "Invalid request";
}
