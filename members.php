<?php
require 'connection.php';

// Fetch users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>
<body>
   <h1>Members</h1>

   <table>
       <tr>
           <th>Name</th>
           <th>Email</th>
           <th>Actions</th>
       </tr>
       <?php
       if ($result->num_rows > 0) {
           // Output data of each row
           while($row = $result->fetch_assoc()) {
               echo "<tr>";
               // Ensure that the array key exists before accessing it
               if(isset($row["FName"])) {
                   echo "<td>" . $row["FName"]. "</td>";
               }
               if(isset($row["email"])) {
                   echo "<td>" . $row["email"]. "</td>";
               }
               echo "<td><a href='update_user.php?id=" . $row["userID"] . "'><i class='material-icons'>edit</i></a>";
               echo "<a href='delete_user.php?id=" . $row["userID"] . "'><i class='material-icons'>delete</i></a></td>";
               echo "</tr>";
           }
       } else {
           echo "<tr><td colspan='3'>0 results</td></tr>";
       }
       $conn->close();
       ?>
   </table>

</body>
</html>
