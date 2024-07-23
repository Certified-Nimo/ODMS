<?php
require 'connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get the last seen users along with their full names
$sql = "SELECT DISTINCT u.userID, CONCAT(u.fName, ' ', u.lName) AS full_name, MAX(m.timestamp) AS last_seen 
        FROM users u
        JOIN messages m ON u.userID = m.sender_id OR u.userID = m.receiver_id
        GROUP BY u.userID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    echo "<table style='margin: auto; border-collapse: collapse; background-color: lightgreen; width: 80%;'>";
    echo "<tr><th style='border: 2px solid black; background-color: #4CAF50; color: white;'>User ID</th><th style='border: 2px solid black; background-color: #4CAF50; color: white;'>Full Name</th><th style='border: 2px solid black; background-color: #4CAF50; color: white;'>Last Seen</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td style='border: 2px solid black;'>".$row["userID"]."</td><td style='border: 2px solid black;'>".$row["full_name"]."</td><td style='border: 2px solid black;'>".$row["last_seen"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>
