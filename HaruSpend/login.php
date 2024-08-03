<?php

@include 'config.php';

session_start();

if (isset($_POST['submit'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $type = $_POST['type'];

   $select = " SELECT * FROM user WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {

      $row = mysqli_fetch_array($result);

      if ($row['type'] == 'admin') {

         $_SESSION['admin_name'] = $row['name'];
         header('location:admin.php');
      } elseif ($row['type'] == 'user') {

         $_SESSION['user_name'] = $row['email'];
         header('location:user.php');
      }
   } else {
      $error[] = 'incorrect email or password!';
   }
};
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login form</title>
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
   <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <style>
      * {
         font-family: "Poppins", sans-serif;
      }
   </style>
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</head>

<body class="bg-[url('image/860-vDWgoEpWu-transformed.jpeg')] bg-no-repeat bg-cover h-screen">
   <header class="flex justify-between p-3 bg-transparent h-[67px] items-center text-white font-medium">

      <div class=" font-bold text-[30px]">
         <span class="text-red-600 hover:text-white">HARU</span>Spend
      </div>
      <span class="text-3xl cursor-pointer pl-40 md:hidden block ">
         <ion-icon name="menu" onclick="Menu(this)"></ion-icon>

      </span>
      
      <div class=" font-bold "></div>
      <ul class="md:flex md:items-center z-[-1] md:z-auto md:static absolute w-full  md:bg-transparent  left-0 md:w-auto md:py-0 py-4 md:pl-0 pl-7 md:opacity-100 opacity-0 top-[-400px] transition-all ease-in  duration-500">
         <li class="mx-4 my-6 md:my-0 curser-pointer ">
            <a href="login.php" class="text-xl hover:text-red-500 duration-500">Home</a>
         </li>
         <li class="mx-4 my-6 md:my-0 curser-pointer">
            <a href="login.php" class="text-xl hover:text-red-500 duration-500">Feedback</a>
         </li>
         <li class="mx-4 my-6 md:my-0 curser-pointer">
            <a href="login.php" class="text-xl hover:text-red-500 duration-500">About us</a>
         </li>
      </ul>
      </div>
      <script>
      function Menu(e) {
         let list = document.querySelector('ul');
         e.name === 'menu' ? (e.name = "close", list.classList.add('top-[80px]'), list.classList.add('opacity-100')) : (e.name = "menu", list.classList.remove('top-[80px]'), list.classList.remove('opacity-100'))
      }
   </script>

   </header>
   
   <div class="flex justify-start item-center m-[100px]">
      <form class="w-full p-6 " action="" method="post">
         <h3 class="text-6xl font-bold my-2 text-white "><span class="text-red-600 hover:text-white">login</span> now</h3>
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            };
         };
         ?>
         <input class=" text-3xl mt-3 rounded-md bg-transparent h-8 text-white m-2" type="email" name="email" required placeholder="enter your email">
         <br>
         <input class="text-3xl mt-3 rounded-md bg-transparent h-7 text-white m-2 " type="password" name="password" required placeholder="enter your password">
         <br>
         <input class=" text-3xl  text-white font-[Poppins] duration-500 m-4 px-20 py-2 mx-4 my-6 md:my-0 hover:bg-red-700 cursor-pointer rounded-md" type="submit" name="submit" value="login now" class="form-btn">
         <p class="text-white text-lg m-2">don't have an account? <a class="   text-white font-[Poppins] duration-500 px-2 py-2  hover:bg-red-700 cursor-pointer text-lg rounded-md" href="register.php">register now</a></p>
      </form>

   </div>


</body>


</html>