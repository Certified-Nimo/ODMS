<?php
// Include the database connection class
require_once 'connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imageUpload"])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $uploadDir = "uploads/"; // Directory where images will be uploaded

    // Create the uploads directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = $_FILES["imageUpload"]["name"]; // Array of uploaded file names
    $photoPaths = array(); // Array to store paths of uploaded photos

    // Loop through each uploaded file
    for ($i = 0; $i < count($uploadedFiles); $i++) {
        $fileName = basename($_FILES["imageUpload"]["name"][$i]); // Get the base name of the uploaded file
        $targetFilePath = $uploadDir . $fileName; // Path to store the uploaded file

        // Check if the file already exists
        if (file_exists($targetFilePath)) {
            echo "File $fileName already exists. Please rename the file and try again.";
        } else {
            // Try to upload the file
            if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"][$i], $targetFilePath)) {
                // File uploaded successfully
                $photoPaths[] = $targetFilePath; // Store the file path
            } else {
                echo "Error uploading file $fileName.";
            }
        }
    }

    // Insert photo paths into the database
    if (!empty($photoPaths)) {
        try {
            $DB = new Database();
            $conn = $DB->connect(); // Get database connection

            // Prepare SQL statement to insert photo paths into the database
            $stmt = $conn->prepare("INSERT INTO userphotos (user_id, photo_path) VALUES (?, ?)");

            // Bind parameters and execute the statement for each photo
            foreach ($photoPaths as $photoPath) {
                $stmt->bind_param('is', $user_id, $photoPath);
                $stmt->execute();
            }

            // Close the statement
            $stmt->close();

            // Close the connection
            $conn->close();

            echo "Photos uploaded successfully!";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
