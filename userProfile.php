<?php
// Assuming you have already started the session and logged in the user

// Include your database connection file
require 'connection.php';
session_start();


// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id']; // Assuming you store user ID in session

// Query to fetch user details from user_details table
$user_details_query = "SELECT * FROM user_details WHERE user_id = $user_id";
$user_details_result = mysqli_query($conn, $user_details_query);
$user_details_row = mysqli_fetch_assoc($user_details_result);

// Query to fetch user photos from userphotos table
$user_photos_query = "SELECT * FROM userphotos WHERE user_id = $user_id";
$user_photos_result = mysqli_query($conn, $user_photos_query);

// Query to fetch username from users table (assuming the column name is 'username')
$users_query = "SELECT * FROM users WHERE userID = $user_id";
$users_result = mysqli_query($conn, $users_query);
$users_row = mysqli_fetch_assoc($users_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="userProfile.css">
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
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>
<div class="profile-container">
    <div class="profile-picture">
        <?php if(mysqli_num_rows($user_photos_result) > 0) {
            $first_photo_row = mysqli_fetch_assoc($user_photos_result);
            $first_photo_path = $first_photo_row['photo_path'];
            echo "<img src='$first_photo_path' alt='User's Profile Picture'>";
        } else {
            echo "<img src='default_profile_picture.jpg' alt='Default Profile Picture'>";
        }
        ?>
    </div>
    <div class="profile-info">
        <h1><?php echo $users_row['fName']; ?></h1>
        <p><?php echo $user_details_row['age'] . ', ' . $user_details_row['gender'] . ', ' . $user_details_row['location']; ?></p>
    </div>
    <div class="about-me">
        <h2>About Me:</h2>
        <p><?php echo $user_details_row['bio']; ?></p>
    </div>
    <div class="interests">
        <h2>My Interests:</h2>
        <ul>
            <li><?php echo $user_details_row['interests']; ?></li>
        </ul>
    </div>
    <div class="preferences">
        <h2>My Preferences:</h2>
        <p>Looking for: <?php echo $user_details_row['looking_for']; ?></p>
        <p>Age Range: <?php echo $user_details_row['age_range']; ?></p>
        <p>Location: <?php echo $user_details_row['preferred_location']; ?></p>
    </div>
    <div class="photos">
        <h2>Photos:</h2>
        <div class="photo-grid">
            <?php
            mysqli_data_seek($user_photos_result, 0); // Resetting pointer
            while($photo_row = mysqli_fetch_assoc($user_photos_result)) {
                echo "<div class='photo-item'>";
                echo "<img src='" . $photo_row['photo_path'] . "' alt='User's uploaded photo'>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <div class="verified-info">
        <h2>Verified Information:</h2>
        <p>Email: <?php echo $users_row['email']; ?></p>
        <p>Phone: <?php echo $users_row['phone']; ?></p>
        <p>Social Media: <?php echo $user_details_row['social_media']; ?></p>
    </div>
    <!-- Additional sections can be added here based on your requirements -->
    <div class="edit-profile">
        <button>Edit Profile</button>
    </div>
</div>
</body>
</html>
