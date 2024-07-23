
<?php
   // check if an image file was uploaded
   if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
      $name = $_FILES['image']['name'];
      $type = $_FILES['image']['type'];
      $data = file_get_contents($_FILES['image']['tmp_name']);
      // connect to the database
      $pdo = new PDO('mysql:host=localhost;dbname=mydb', 'username', 'password');
      // insert the image data into the database
      $stmt = $pdo->prepare("INSERT INTO userphotos (photo_id,user_id,photopath) VALUES (?, ?, ?)");
      $stmt->bindParam(1, $name);
      $stmt->bindParam(2, $type);
      $stmt->bindParam(3, $data);
      $stmt->execute();
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="post" action="upload.php" enctype="multipart/form-data">
   <input type="file" name="image" />
   <input type="submit" name="submit" value="Upload" />
</form>
    
</body>
</html>