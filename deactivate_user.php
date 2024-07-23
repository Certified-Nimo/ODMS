<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['memberId'])) {
    $memberId = $_POST['memberId'];

    // Update the status to 'inactive'
    $query = "UPDATE members SET status = 'inactive' WHERE memberId = $memberId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Provide a success message or redirect to a success page
        echo "Account deactivated successfully!";
    } else {
        // Provide an error message
        echo "Error deactivating account: " . mysqli_error($conn);
    }
} else {
    // Handle invalid requests
    echo "Invalid request.";
}
?>
