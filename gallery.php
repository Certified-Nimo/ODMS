<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Gallery</title>
    <link rel="stylesheet" href="gallery.css">
    <style>
        /* CSS for image display */
        .image-table {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .image-row {
            display: table-row;
        }

        .image-cell {
            display: table-cell;
            padding: 10px;
            text-align: center;
        }

        .image {
            max-width: 300px;
            max-height: 200px;
            width: auto;
            height: auto;
        }

        .delete-button {
            margin-top: 5px;
            padding: 5px 10px;
            background-color: #ff6347;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Navigation</h2>
        <ul>
            <li><a href="userProfile.php">Edit Profile</a></li>
            <li><a href="matches.php">My Matches</a></li>
            <li><a href="messages.php">Messages</a></li>
            <li><a href="gallery.php">My Gallery</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>
    <div class="gallery-container">
        <h1>My Gallery</h1>
        <div id="uploadForm">
            <input type="file" name="image[]" id="imageUpload" multiple>
            <button onclick="uploadImages()">Upload</button>
        </div>

        <div id="gallery" class="image-table">
            <?php
            require_once 'connection.php';
            session_start();

            // Check if the user is logged in
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit();
            }

            function displayImagesFromDatabase() {
                try {
                    $DB = new Database();
                    $conn = $DB->connect();
                    $userId = $_SESSION['user_id'];

                    // Retrieve images from the database
                    $sql = "SELECT * FROM userphotos WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Display images in a table format
                    echo "<div class='image-row'>";
                    while ($row = $result->fetch_assoc()) {
                        $imageId = $row['photo_id'];
                        $imagePath = $row['photo_path'];

                        echo "<div class='image-cell'>";
                        echo "<img src='$imagePath' alt='Image' class='image'>";
                        echo "<button class='delete-button' onclick='deleteImage($imageId)'>Delete</button>";
                        echo "</div>";
                    }
                    echo "</div>";

                    // Close the statement and connection
                    $stmt->close();
                    $conn->close();
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

            // Call the function to display images
            displayImagesFromDatabase();
            ?>
        </div>
    </div>

    <script>
        // Function to upload images
        function uploadImages() {
            var formData = new FormData();
            var files = document.getElementById("imageUpload").files;
            for (var i = 0; i < files.length; i++) {
                formData.append("imageUpload[]", files[i]);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "uploadpic.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    // Optionally, refresh the gallery after successful upload
                    refreshGallery();
                } else {
                    alert("Error uploading images: " + xhr.statusText);
                }
            };
            xhr.send(formData);
        }

        // Function to delete image
        function deleteImage(imageId) {
            if (confirm("Are you sure you want to delete this image?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "deletepic.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert(xhr.responseText);
                        // Refresh the gallery after successful deletion
                        refreshGallery();
                    } else {
                        alert("Error deleting image: " + xhr.statusText);
                    }
                };
                xhr.send("imageId=" + imageId);
            }
        }

        // Function to refresh the gallery
        function refreshGallery() {
            document.getElementById("gallery").innerHTML = "";
            <?php displayImagesFromDatabase(); ?>
        }
    </script>
</body>
</html>
