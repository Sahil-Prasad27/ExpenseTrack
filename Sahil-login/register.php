<?php

@include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    if (strlen($pass) < 8) {
        $error[] = 'Password must be at least 8 characters long';
    } elseif ($pass !== $cpass) {
        $error[] = 'Passwords do not match';
    } else {
        $pass = md5($pass);

        
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error[] = 'User already exists!';
        } else {
            $stmt = $conn->prepare("INSERT INTO user(name, email, password, type) VALUES(?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $pass, $user_type);
            $stmt->execute();
            header('location:login.php');
        }

        
        $stmt->close();
    }
}

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
        </select>

        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</div>
</body>
</html>
