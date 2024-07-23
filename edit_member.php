<?php
require 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userID = $_POST['userID'];
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
   
    // Update user information in the database
    $query = "UPDATE users SET fName = '$fName', lName = '$lName', phone = '$phone', email = '$email' WHERE userID = $userID";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Display a success or error message
    if ($result) {
        $message = 'User information updated successfully!';
        $color = 'var(--color-success)';
    } else {
        $message = 'Error updating user: ' . mysqli_error($conn);
        $color = 'red';
    }
}

// Fetch user details from the database based on userID
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    // Fetch user details
    $query = "SELECT * FROM users WHERE userID = $userID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "User ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit_member.css">
</head>
<body>
    <h2>► Edit User Details</h2>

    <!-- Form to edit user information -->
    <form action="" method="post">
        <input type="hidden" name="userID" value="<?php echo $userData['userID']; ?>">
        <label for="fName">First Name:</label>
        <input type="text" id="fName" name="fName" value="<?php echo $userData['fName']; ?>" required><br>

        <label for="lName">Last Name:</label>
        <input type="text" id="lName" name="lName" value="<?php echo $userData['lName']; ?>" required><br>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $userData['phone']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userData['email']; ?>" required><br>

        <button type="submit" style="background-color: green; color: white;">Update</button>

        <?php if (isset($message)) : ?>
            <p class="message" style="color: <?php echo $color; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
    </form>

    <button class="back">
        <a href="admin1.php">Back</a>
    </button> 
</body>
</html>
