<?php
session_start();

include 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind parameters
    $query = "INSERT INTO user_details (user_id, gender, age, location, bio, interests, looking_for, age_range, preferred_location, social_media) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisssssss", $user_id, $gender, $age, $location, $bio, $interests, $looking_for, $age_range, $preferred_location, $social_media);

    // Set parameters
    $user_id = $_SESSION['user_id']; 
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $location = $_POST['location'];
    $bio = $_POST['bio'];
    $interests = implode(",", $_POST['interest']);
    $looking_for = $_POST['lookingFor'];
    $age_range = $_POST['ageRange'];
    $preferred_location = $_POST['preferredLocation'];
    $social_media = $_POST['socialMedia'];

    // Execute statement
    if ($stmt->execute()) {
        header("Location: userProfile.php");
        exit(); // Ensure script stops executing after redirection
    } else {
        echo "Error creating profile.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Dating Management System</title>
    <link rel="stylesheet" href="userDet.css">
</head>
<body>
    <!-- Profile Form Modal -->
    <div id="profileForm" class="modal hidden">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <form id="profileDetailsForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Profile Details</h2>
                <label for="age">What is your age?:</label>
                <input type="text" id="age" name="age">

                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                    <option value="other">Other</option>
                </select>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location">

                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"></textarea>

                <label for="interest">Interest:</label>
                <select id="interest" name="interest[]" multiple>
                    <option value="music">Music</option>
                    <option value="sports">Sports</option>
                    <option value="movies">Movies</option>
                    <option value="books">Books</option>
                    <option value="travel">Travel</option>
                    <option value="fitness">Fitness</option>
                </select>

                <label for="lookingFor">Looking For:</label>
                <select id="lookingFor" name="lookingFor" multiple>
                    <option value="">Select</option>
                    <option value="friendship">Friendship</option>
                    <option value="dating">Dating</option>
                    <option value="relationship">Relationship</option>
                    <option value="networking">Networking</option>
                </select>

                <label for="ageRange">Age Range:</label>
                <select id="ageRange" name="ageRange">
                    <option value="">Select Age Range</option>
                    <option value="18-25">18-25</option>
                    <option value="26-35">26-35</option>
                    <option value="36-45">36-45</option>
                    <option value="46-55">46-55</option>
                    <option value="56+">56+</option>
                </select>

                <label for="preferredLocation">Preferred Location:</label>
                <input type="text" id="preferredLocation" name="preferredLocation">

                <label for="socialMedia">Social Media:</label>
                <input type="text" id="socialMedia" name="socialMedia">

                <button type="submit">Create</button>
            </form>
        </div>
    </div>
    
    <!-- Update Profile Button -->
    <div class="update-profile-container">
        <button id="openModal" class="update-profile-button">Create Your Profile</button>
    </div>

    <script>
        document.getElementById('openModal').addEventListener('click', function() {
            document.getElementById('profileForm').style.display = 'block';
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('profileForm').style.display = 'none';
        });

        // Optional: Close the modal if the user clicks anywhere outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('profileForm');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>
