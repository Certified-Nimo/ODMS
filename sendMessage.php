<?php
session_start();
if(isset($_SESSION['user_id']) && isset($_POST['receiver_id']) && isset($_POST['message_content'])) {
    include 'connection.php';
    
    $sender_id = $_SESSION['user_id']; 
    $receiver_id = $_POST['receiver_id'];
    $message_content = $_POST['message_content'];

    // Insert the message into the database
    $insert_query = "INSERT INTO messages (sender_id, receiver_id, message_content, timestamp) VALUES ($sender_id, $receiver_id, '$message_content', NOW())";
    mysqli_query($conn, $insert_query);

    // Redirect back to the messaging interface
    header("Location: messages.php?match_id=$receiver_id"); // Redirect to the conversation with the receiver
    exit();
} else {
    // Redirect if user is not logged in or required parameters are not provided
    header("Location: login.php");
    exit();
}
?>
