<?php
session_start();
if(isset($_SESSION['user_id'])) {
    include 'connection.php';
    
    // Fetch all conversations for the logged-in user
    $user_id = $_SESSION['user_id'];
    $conversations_query = "SELECT DISTINCT u.userID, u.fName, 
    (SELECT up.photo_path FROM userphotos up WHERE up.user_id = u.userID LIMIT 1) AS photo_path
    FROM users u
    WHERE u.userID IN (
        SELECT DISTINCT sender_id AS user_id
        FROM messages
        WHERE receiver_id = $user_id
        UNION
        SELECT DISTINCT receiver_id AS user_id
        FROM messages
        WHERE sender_id = $user_id
    )
    AND u.userID != $user_id
    ORDER BY u.fName ASC";

    $conversations_result = mysqli_query($conn, $conversations_query);

    // Display messaging dashboard
    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Messages</title>";
    echo "<link rel='stylesheet' href='messages.css'>"; // Include CSS here
    echo "</head>";
    echo "<body>";

    echo "<div class='container'>";
    echo "<aside class='sidebar'>";
    echo "<div class='sidebar-header'>";
    echo "<h1 class='sidebar-title'>Conversations</h1>";
    echo "</div>";
    echo "<div class='user-list'>";

    // Display each conversation in the sidebar
    while($conversation_row = mysqli_fetch_assoc($conversations_result)) {
        $match_id = $conversation_row['userID'];
        $match_name = $conversation_row['fName'];
        $match_profile_picture = $conversation_row['photo_path'];

        echo "<div class='user'>";
        echo "<a href='messages.php?match_id=$match_id&match_name=" . urlencode($match_name) . "'>";
        echo "<div class='user-image'><img src='$match_profile_picture' alt='Profile Picture'></div>";
        echo "<div class='user-details'>";
        echo "<div class='user-name'>$match_name</div>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }

    echo "</div>"; // Close user-list div
    echo "</aside>"; // Close sidebar div

    echo "<main class='chat'>";
    
    // Display messages for the selected conversation
    if(isset($_GET['match_id']) && isset($_GET['match_name'])) {
        $match_id = $_GET['match_id'];
        $match_name = $_GET['match_name'];

        // Fetch profile picture of the match from userphotos table
        $user_photos_query = "SELECT * FROM userphotos WHERE user_id = $match_id LIMIT 1"; 
        $user_photos_result = mysqli_query($conn, $user_photos_query);
        if(mysqli_num_rows($user_photos_result) > 0) {
            $photo_row = mysqli_fetch_assoc($user_photos_result);
            $match_profile_picture = $photo_row['photo_path'];
        } else {
            $match_profile_picture = 'default_profile_picture.jpg';
        }

        // Fetch previous messages between logged-in user and matched user
        $messages_query = "SELECT * FROM messages WHERE (sender_id = $user_id AND receiver_id = $match_id) OR (sender_id = $match_id AND receiver_id = $user_id) ORDER BY timestamp ASC";
        $messages_result = mysqli_query($conn, $messages_query);

        // Display chat header
        echo "<header class='chat-header'>";
        echo "<div class='chat-header-info'>";
        echo "<div class='chat-partner-image'><img src='$match_profile_picture' alt='Profile Picture'></div>";
        echo "<div class='chat-partner-name-status'>";
        echo "<div class='chat-partner-name'>$match_name</div>";
        echo "<div class='chat-partner-status'>Online</div>"; // You can update this status dynamically
        echo "</div>";
        echo "</div>";
        echo "</header>";

        // Display chat history
        echo "<div class='chat-history'>";
        while($message_row = mysqli_fetch_assoc($messages_result)) {
            $message_content = $message_row['message_content'];
            $message_class = ($message_row['sender_id'] == $user_id) ? 'sent' : 'received';
            echo "<div class='message $message_class'>$message_content</div>";
        }
        echo "</div>"; // Close chat-history div

        // Display chat input form
        echo "<footer class='chat-input'>";
        echo "<form action='sendMessage.php' method='POST'>"; // Assuming you have a script to handle message sending
        echo "<input type='hidden' name='receiver_id' value='$match_id'>";
        echo "<input type='text' name='message_content' placeholder='Type a message...'>";
        echo "<img src='img/send-button.png' alt='Send Message' class='send-button' width='40' height='40'>";
        echo "</form>";
        echo "</footer>";
    }

    echo "</main>"; // Close chat div
    echo "</div>"; // Close container div

    echo "</body>";
    echo "</html>";
} else {
    // Redirect if user is not logged in
    header("Location: login.php");
    exit();
}
