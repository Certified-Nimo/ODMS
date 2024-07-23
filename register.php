<?php
require 'connection.php';

if (isset($_POST["submit"])) {    
    $fName = $_POST["fName"];
    $lName = $_POST["lName"];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {               
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (fName, lName, email, phone, password, registration_date) VALUES (?, ?, ?, ?, ?, NOW())";
        $preparedSql = mysqli_prepare($conn, $query);

        if ($preparedSql) {
            mysqli_stmt_bind_param($preparedSql, 'sssss', $fName, $lName, $email, $phone, $hashedPassword);
            mysqli_stmt_execute($preparedSql);
            mysqli_stmt_close($preparedSql);

            echo "<script>alert('Your Account has been Created Successfully');</script>";
            // Redirect only after successful account creation
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Error in prepared statement');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="admin.css">
    <title>Register</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; 
    background: linear-gradient(to top, rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%), url(img/1.jpg);">

  
    <form action ="" method="post">
    
    <div class="login-container">
        <div class="image-container">
            <img src="img/logo1.png" alt="Login Image">
        </div>
        <div class="form-container">
            <div class="register-form">
                <h2>Register</h2>
                
                <div class="form-group">
                    <label for="firstname">First Name<span>*</span></label>
                    <input type="text" id="fName" name="fName" placeholder="e.g Andrew" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name<span>*</span></label>
                    <input type="text" id="lName" name="lName" placeholder="e.g maina" required>
                </div>
                <div class="form-group">
                    <label for="email">Email<span>*</span></label>
                    <input type="email" id="email" name="email" placeholder="e.g abc@gmail.com" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number<span>*</span></label>
                    <input type="text" id="phone" name="phone" pattern="07\d{8}" placeholder="e.g 0712345678" required>
                    <small class="text-muted">Please enter a 10-digit phone number starting with '07'.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password<span>*</span></label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <input type="password" id="ConfirmPassword" name="confirmPassword" required>
                    <p class="error-message" id="passwordError"></p>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit">Register</button>
                </div>
                <div class="acc">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </div>
        </div>
    </div>

    </form>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var errorElement = document.getElementById("passwordError");

            if (password !== confirmPassword) {
                errorElement.textContent = "Passwords do not match!";
                return false;
            } else {
                errorElement.textContent = "";
                return true;
            }
        }
    </script>
</body>
</html>