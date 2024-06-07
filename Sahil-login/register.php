<?php

@include 'config.php';

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    
    if(strlen($pass) < 8) {
        $error[] = 'Password must be at least 8 characters long';
    } elseif ($pass !== $cpass) {
        $error[] = 'Passwords do not match';
    } else {
        $pass = md5($pass);  
        $cpass = md5($cpass); 
        
        $select = "SELECT * FROM user WHERE email = '$email'";

        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0){
            $error[] = 'User already exists!';
        } else {
            $insert = "INSERT INTO user(name, email, password, type) VALUES('$name', '$email', '$pass', '$user_type')";
            mysqli_query($conn, $insert);
            header('location:login.php');    
        }
    }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<div class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>

        <?php
        if(isset($error)){
            foreach($error as $error){
                echo '<span class="error-msg">'.$error.'</span>';
            };
        };
        ?>

        <input type="text" name="name" required placeholder="Enter your name">
        <input type="email" name="email" required placeholder="Enter your email">
        <input type="password" name="password" required placeholder="Enter your password (min 8 characters)" pattern=".{8,}">
        <input type="password" name="cpassword" required placeholder="Confirm your password" pattern=".{8,}">
        <select name="user_type">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</div>
</body>
</html>
