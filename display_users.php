<?php
require 'connection.php';

// Fetch all users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Check if users exist
if (mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Display Users</title>
        <link rel="stylesheet" href="admin.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp">
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                background-color: lightgrey;
            }

            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: lightblue;
            }
        </style>
    </head>
    <body>
        <h2>► Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through each user and display details
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['userID'] . "</td>";
                    echo "<td>" . $row['fName'] . "</td>";
                    echo "<td>" . $row['lName'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>
                            <a href='edit_member.php?userID=" . $row['userID'] . "'><i class='material-icons-sharp'>edit</i></a>
                            <a href='delete_user.php?userID=" . $row['userID'] . "'><i class='material-icons-sharp'>delete</i></a>
                            <a href='suspendUser.php?userID=" . $row['userID'] . "'><i class='material-icons-sharp'>block</i></a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
} else {
    echo "No users found.";
}
?>
