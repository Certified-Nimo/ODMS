<?php
session_start();
if(isset($_SESSION['user_id'])) {
    include 'connection.php'; // Include your database connection

    $user_id = $_SESSION['user_id'];

    // Fetch conversations involving the logged-in user
    $conversations_query = "SELECT DISTINCT sender_id, receiver_id FROM messages WHERE sender_id = $user_id OR receiver_id = $user_id";
    $conversations_result = mysqli_query($conn, $conversations_query);

    // Check if there are conversations
    if(mysqli_num_rows($conversations_result) > 0) {
        echo "<!DOCTYPE html>";
        echo "<html lang='en'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Conversations</title>";
        echo "<link rel='stylesheet' href='conversations.css'>"; // Include CSS here
        echo "</head>";
        echo "<body>";

        echo "<div class='container'>";
        echo "<h1>Conversations</h1>";

        // Display list of conversations
        echo "<ul>";
        while($conversation_row = mysqli_fetch_assoc($conversations_result)) {
            $other_user_id = ($conversation_row['sender_id'] != $user_id) ? $conversation_row['sender_id'] : $conversation_row['receiver_id'];
            
            // Fetch details of the other user in the conversation
            $other_user_query = "SELECT * FROM users WHERE userID = $other_user_id";
            $other_user_result = mysqli_query($conn, $other_user_query);
            $other_user_row = mysqli_fetch_assoc($other_user_result);

            // Display conversation link
            echo "<li>";
            echo "<a href='conversation.php?other_user_id=$other_user_id&other_user_name=" . urlencode($other_user_row['fName']) . "'>";
            echo $other_user_row['fName']; // Display other user's username
            echo "</a>";
            echo "</li>";
        }
        echo "</ul>";

        echo "</div>"; // Close container div

        echo "</body>";
        echo "</html>";
    } else {
        // If no conversations found
        echo "No conversations found.";
    }
} else {
    // Redirect if user is not logged in
    header("Location: login.php");
    exit();
}
?>
