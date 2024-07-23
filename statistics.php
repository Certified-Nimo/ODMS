<?php
require 'connection.php';

// Check if 'page' parameter is set and equals 'dashboard'
if(isset($_GET['page']) && $_GET['page'] === 'dashboard') {
    // Count total number of users
    $sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM user_details";
    $resultTotalUsers = $conn->query($sqlTotalUsers);
    $rowTotalUsers = $resultTotalUsers->fetch_assoc();
    $totalUsers = $rowTotalUsers['total_users'];

    // Count male users
    $sqlMaleUsers = "SELECT COUNT(*) AS male_users FROM user_details WHERE gender = 'male'";
    $resultMaleUsers = $conn->query($sqlMaleUsers);
    $rowMaleUsers = $resultMaleUsers->fetch_assoc();
    $maleUsers = $rowMaleUsers['male_users'];

    // Count female users
    $sqlFemaleUsers = "SELECT COUNT(*) AS female_users FROM user_details WHERE gender = 'female'";
    $resultFemaleUsers = $conn->query($sqlFemaleUsers);
    $rowFemaleUsers = $resultFemaleUsers->fetch_assoc();
    $femaleUsers = $rowFemaleUsers['female_users'];

    /* Count subscribed users
    $sqlSubscribedUsers = "SELECT COUNT(*) AS subscribed_users FROM user_details WHERE subscribed = 1";
    $resultSubscribedUsers = $conn->query($sqlSubscribedUsers);
    $rowSubscribedUsers = $resultSubscribedUsers->fetch_assoc();
    $subscribedUsers = $rowSubscribedUsers['subscribed_users']; */

    // Calculate percentage of male and female users
    $totalGenderCount = $maleUsers + $femaleUsers;
    $malePercentage = ($totalGenderCount > 0) ? round(($maleUsers / $totalGenderCount) * 100, 2) : 0;
    $femalePercentage = ($totalGenderCount > 0) ? round(($femaleUsers / $totalGenderCount) * 100, 2) : 0;

    // Data for the pie chart
    $genderData = [
        ['Gender', 'Count'],
        ['Male', $maleUsers],
        ['Female', $femaleUsers]
    ];
} else {
    // If 'page' parameter is not set or doesn't equal 'dashboard', set default values
    $totalUsers = 0;
    $maleUsers = 0;
    $femaleUsers = 0;
    $subscribedUsers = 0;
    $malePercentage = 0;
    $femalePercentage = 0;
    $genderData = [];
}


// Return statistics data
echo json_encode([
    'totalUsers' => $totalUsers,
    'maleUsers' => $maleUsers,
    'femaleUsers' => $femaleUsers,
 //   'subscribedUsers' => $subscribedUsers,
    'malePercentage' => $malePercentage,
    'femalePercentage' => $femalePercentage,
    'genderData' => $genderData
]);
?>
