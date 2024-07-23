<?php
require 'connection.php';
function displayImagesFromDatabase() {
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in and session contains user_id
    if (isset($_SESSION['user_id'])) {
        try {
            $DB = new Database();
            $conn = $DB->connect(); // Get database connection

            // Prepare and execute SQL query to select photo paths from userphotos table
            $stmt = $conn->prepare("SELECT photo_path FROM userphotos WHERE user_id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']); // Bind user ID from session
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any photos in the database
            if ($result->num_rows > 0) {
                // Loop through each row and display the image
                while ($row = $result->fetch_assoc()) {
                    // Output image tag directly to HTML
                    echo '<img src="http://localhost/ODBMS2/' . htmlspecialchars($row['photo_path'], ENT_QUOTES) . '" alt="User uploaded image" class="gallery-img">';
                }
            } else {
                echo "No images found in the database."; // If no images found
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage(); // Print any exceptions
        }
    } else {
        echo "User session not set."; // If user session is not set
    }
}
