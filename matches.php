<?php
session_start();
if(isset($_SESSION['user_id'])) {
    include 'connection.php';
    $user_id = $_SESSION['user_id']; 

    // Fetch gender of logged-in user from user_details table
    $user_details_query = "SELECT gender FROM user_details WHERE user_id = $user_id";
    $user_details_result = mysqli_query($conn, $user_details_query);
    $user_details_row = mysqli_fetch_assoc($user_details_result);
    $user_gender = $user_details_row['gender'];

    // Query to fetch possible matches based on user's criteria
    $matches_query = "SELECT u.*, ud.* FROM users u
                      INNER JOIN user_details ud ON u.userID = ud.user_id
                      WHERE u.userID != $user_id AND ud.gender != '$user_gender'
                      ORDER BY ud.age ASC, ud.location ASC"; 
    $matches_result = mysqli_query($conn, $matches_query);

    // Check if there are any matches
    if(mysqli_num_rows($matches_result) > 0) {
        echo "<main class='match-display'>"; 
        $counter = 0; // Counter for unique identifiers
        while($match_row = mysqli_fetch_assoc($matches_result)) {
            $counter++; // Increment counter
            echo "<div class='match-profile' data-match-id='" . $match_row['userID'] . "' data-match-name='" . $match_row['fName'] . "'>";
            echo "<img src='" . getProfilePicture($conn, $match_row['userID']) . "' alt='Profile Picture'>";
            echo "<h2>" . $match_row['fName'] . "</h2>";
            echo "<p>Age: " . $match_row['age'] . "</p>";
            echo "<p>Location: " . $match_row['location'] . "</p>";
            echo "<p>You both have interest in: " . $match_row['interests'] . "</p>";
            echo "<img class='like-button' src='img/love.png' alt='Like'>";
            echo "</div>"; // Close match-profile div
        }
        echo "</main>"; // Close match-display div
    } else {
        echo "<div class='match-display'><p>No matches found.</p></div>"; 
    }
    mysqli_close($conn);
} else {
    header("Location: login.php");
    exit(); 
}

function getProfilePicture($conn, $user_id) {
    $user_photos_query = "SELECT * FROM userphotos WHERE user_id = $user_id LIMIT 1"; 
    $user_photos_result = mysqli_query($conn, $user_photos_query);
    if(mysqli_num_rows($user_photos_result) > 0) {
        $photo_row = mysqli_fetch_assoc($user_photos_result);
        return $photo_row['photo_path'];
    } else {
        return 'default_profile_picture.jpg';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My matches</title>
    <link rel="stylesheet" href="matches.css">
</head>
<body>

    <aside class="sidebar">
        <ul>
            <li><a href="userProfile.php">Edit Profile</a></li>
            <li><a href="matches.php">My Matches</a></li>
            <li><a href="messages.php">Messages</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </aside> 

    <div class="match-navigation">
    <img class="prev-button" src="img/left-arrow.png" alt="Previous">
        <img class="next-button" src="img/next.png" alt="Next">
        
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    var matchProfiles = document.querySelectorAll('.match-profile');
    var currentMatchIndex = 0;

    function showMatch(index) {
        matchProfiles.forEach(function(profile, idx) {
            if (idx === index) {
                profile.style.display = 'block';
            } else {
                profile.style.display = 'none';
            }
        });
    }

    showMatch(currentMatchIndex);

    document.querySelector('.prev-button').addEventListener('click', function() {
        currentMatchIndex = (currentMatchIndex - 1 + matchProfiles.length) % matchProfiles.length;
        showMatch(currentMatchIndex);
    });

    document.querySelector('.next-button').addEventListener('click', function() {
        currentMatchIndex = (currentMatchIndex + 1) % matchProfiles.length;
        showMatch(currentMatchIndex);
    });

    document.querySelectorAll('.like-button').forEach(function(likeButton) {
        likeButton.addEventListener('click', function() {
            const matchProfile = likeButton.closest('.match-profile');
            const matchId = matchProfile.getAttribute('data-match-id');
            const matchName = matchProfile.getAttribute('data-match-name');
            
            // Redirect to messaging interface with match details
            window.location.href = `messages.php?match_id=${matchId}&match_name=${matchName}`;
        });
    });

    
});


    </script>
</body>
</html>
