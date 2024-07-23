<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Here, you would implement your suspension logic, for example, updating a 'suspended' flag in the database
    // For demonstration, let's just print a message
    echo "User with ID $userID has been suspended.";

} else {
    echo "Invalid request";
}
