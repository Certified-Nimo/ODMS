<?php
require_once 'connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit();
}

// Check if the image ID is provided in the request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["imageId"])) {
    $imageId = $_POST["imageId"]; // Get the image ID from the request

    try {
        $DB = new Database();
        $conn = $DB->connect(); // Get database connection

        // Prepare SQL statement to delete the image from the database
        $stmt = $conn->prepare("DELETE FROM userphotos WHERE photo_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $imageId, $_SESSION['user_id']);
        $stmt->execute();

        // Close the statement
        $stmt->close();

        // Check if any rows were affected
        if ($conn->affected_rows > 0) {
            // Delete the image file from the "uploads" folder
            $imagePathQuery = "SELECT photo_path FROM userphotos WHERE photo_id = ?";
            $stmt = $conn->prepare($imagePathQuery);
            $stmt->bind_param('i', $imageId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $imagePath = $result->fetch_assoc()["photo_path"];
                unlink($imagePath); // Delete the file
                echo "Image deleted successfully!";
            } else {
                echo "Image not found!";
            }
        } else {
            echo "You do not have permission to delete this image.";
        }

        // Close the connection
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request";
}
?>
