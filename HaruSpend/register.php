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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                screens: {
                    xs: "300px",
                    sm: "640px",
                    md: "768px",
                    lg: "1024px",
                    xl: "1280px",
                    "2xl": "1536px",
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * {
            font-family: "Poppins", sans-serif;
        }
    </style>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="bg-[url('image/inside.png')] bg-no-repeat bg-cover min-h-screen">
    <header class="flex justify-between p-3 bg-transparent h-[67px] items-center text-white font-medium">
        <div class=" font-bold text-[30px]">
            <span class="text-red-600 hover:text-cyan-500">HARU</span>Spend
        </div>
        <span class="text-3xl cursor-pointer pl-40 md:hidden block ">
            <ion-icon name="menu" onclick="Menu(this)"></ion-icon>
        </span>
        <div class=" font-bold "></div>
        <ul class="md:flex md:items-center z-[-1] md:z-auto md:static absolute w-  md:bg-transparent  left-0 md:w-auto md:py-0 py-4 md:pl-0 pl-7 md:opacity-100 opacity-0 top-[-400px] transition-all ease-in  duration-500">
            <li class="mx-4 my-6 md:my-0 curser-pointer">
                <a href="login.php" class="text-xl hover:text-cyan-500 duration-500">About us</a>
            </li>
        </ul>
        <script>
            function Menu(e) {
                let list = document.querySelector('ul');
                e.name === 'menu' ? (e.name = "close", list.classList.add('top-[80px]'), list.classList.add('opacity-100')) : (e.name = "menu", list.classList.remove('top-[80px]'), list.classList.remove('opacity-100'))
            }
        </script>
    </header>

    <div class="flex items-center justify-center min-h-screen text-white font-bold">
        <div class="flex flex-col md:flex-row items-center">
            <div>
                <h1 class="text-white font-bold text-4xl ml-[557px]  mb-3 "><span class="text-[#deb887] hover:text-red-600 text-6xl">Register</span> Now</h1>
                <form class="bg-opacity-50 p-6 rounded-lg shadow-lg" action="" method="post">
                    <?php
                    if (isset($error)) {
                        foreach ($error as $error) {
                            echo '<span class="error-msg">' . $error . '</span>';
                        };
                    };
                    ?>
                    <div class="ml-[500px]">
                        <div class="input-section flex flex-col items-center text-black ">
                            <input class="text-xl w-[400px] max-w-lg pt-2 pl-2 m-2" type="text" name="name" required placeholder="Enter your name">
                            <input class="text-xl w-[400px] max-w-lg pt-2 pl-2 m-2" type="email" name="email" required placeholder="Enter your email">
                            <input class="text-xl w-[400px] max-w-lg pt-2 pl-2 m-2" type="password" name="password" required placeholder="Enter your password (min 8 characters)" pattern=".{8,}">
                            <input class="text-xl w-[400px] max-w-lg pt-2 pl-2 m-2" type="password" name="cpassword" required placeholder="Confirm your password" pattern=".{8,}">
                            <select name="user_type " class="text-xl text-black w-[400px] pt-2 pl-2 m-2">
                                <option value="user">User</option>
                            </select>
                        </div>
                        <input type="submit" name="submit" value="Register Now" class="text-2xl bg-blue-900 text-white font-[Poppins] duration-500 p-3 ml-[120px] rounded-md hover:bg-red-700 cursor-pointer mt-4">
                        <p class="mt-4 ml-[85px]">Already have an account? <a href="login.php" class="text-md text-white font-[Poppins] duration-500   rounded-md hover:bg-red-700 cursor-pointer mt-4">Login Now</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
