<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit();
}

@include 'config.php';

if (isset($_GET['edit'])) {
    $ide = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM expenses WHERE ide='$ide'");
    $expense = mysqli_fetch_array($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ide = $_POST['ide'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "UPDATE expenses SET category=?, amount=?, date=?, description=? WHERE ide=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdssi', $category, $amount, $date, $description, $ide);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Expense</title>
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

<body class="bg-[url('image/inside.png')] bg-no-repeat bg-cover min-h-screen">
    <header class="flex justify-between p-3 bg-transparent h-[67px] items-center text-white font-medium">

        <div class=" font-bold text-[30px]">
            <span class="text-red-600 hover:text-cyan-500">HARU</span>Spend
        </div>
        <span class="text-3xl cursor-pointer pl-40 md:hidden block ">
            <ion-icon name="menu" onclick="/Menu(this)"></ion-icon>

        </span>

        <div class=" font-bold "></div>
        <ul class="md:flex md:items-center z-[-1] md:z-auto md:static absolute w-full  md:bg-transparent  left-0 md:w-auto md:py-0 py-4 md:pl-0 pl-7 md:opacity-100 opacity-0 top-[-400px] transition-all ease-in  duration-500">
            <li class="mx-4 my-6 md:my-0 curser-pointer">
                <a href="chart.php" class="btn text-white text-xl hover:text-cyan-500 duration-500">View chart</a>
            </li>
            <li class="mx-4 my-6 md:my-0 curser-pointer">
                <a href="login.php" class="text-xl hover:text-cyan-500 duration-500">Feedback</a>
            </li>
            <li class="mx-4 my-6 md:my-0 curser-pointer">
                <a href="login.php" class="text-xl hover:text-cyan-500 duration-500">About us</a>
            </li>
            <li>
                <div class="flex justify-end pr-6 gap-2 m-2">
                    <a href="logout.php" class="btn text-white text-xl hover:text-cyan-500 duration-500">Logout</a>
                </div>
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
    <div class="text-center p-4">
        <h1 class="text-white font-bold text-4xl "><span class="text-[#deb887] hover:text-red-600 text-6xl">Update</span> Expense</h1>
        <div class="input-section flex justify-end m-4 pr-40 pt-20">
            <form class="text-xl text-left flex flex-col w-full max-w-lg pt-3" method="post" action="">
                <input  type="hidden" name="ide" value="<?php echo $expense['ide']; ?>">
                <label class="text-white font-bold" for="category-select">Category:</label>
                <select id="category-select" name="category" required>
                    <option value="Food & Beverage" <?php echo ($expense['category'] == 'Food & Beverage') ? 'selected' : ''; ?>>Food & Beverage</option>
                    <option value="Rent" <?php echo ($expense['category'] == 'Rent') ? 'selected' : ''; ?>>Rent</option>
                    <option value="Transport" <?php echo ($expense['category'] == 'Transport') ? 'selected' : ''; ?>>Transport</option>
                    <option value="Relaxing" <?php echo ($expense['category'] == 'Relaxing') ? 'selected' : ''; ?>>Relaxing</option>
                </select>
                <label class="text-white font-bold pt-2" for="amount-input">Amount:</label>
                <input type="number" id="amount-input" name="amount" value="<?php echo $expense['amount']; ?>" required>
                <label class="text-white font-bold pt-2" for="date-input">Date:</label>
                <input type="date" id="date-input" name="date" value="<?php echo $expense['date']; ?>" required>
                <label class="text-white font-bold pt-2" for="description-input">Description:</label>
                <input type="text" id="description-input" name="description" value="<?php echo $expense['description']; ?>">
                <button class="text-2xl pt-2 bg-blue-900/30 text-white font-[Poppins] duration-500 p-3 rounded-md hover:bg-red-700 cursor-pointer"  type="submit" id="update-btn">Update</button>
                <h1 class=" font-bold pl-[122px] text-white">click on update to go back</h1class>
            </form>
        </div>
    </div>
</body>

</html>