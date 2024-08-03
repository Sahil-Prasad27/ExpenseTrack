<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit();
}

@include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_SESSION['user_name'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$user'");
    $row = mysqli_fetch_array($query);
    $id = $row['id'];
    $user_id = $id;
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO expenses (user_id, category, amount, date, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isdss', $user_id, $category, $amount, $date, $description);
    $stmt->execute();

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


$date_filter = '';
$status_filter = '';
$filter_query = '';

if (isset($_GET['date']) && $_GET['date'] != '') {
    $date_filter = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
    $filter_query .= " AND date = '$date_filter'";
}
if (isset($_GET['status']) && $_GET['status'] != '') {
    $status_filter = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
    $filter_query .= " AND category = '$status_filter'";
}

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'date';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$next_order = $sort_order == 'asc' ? 'desc' : 'asc';

$user = $_SESSION['user_name'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$user'");
$row = mysqli_fetch_array($query);
$id = $row['id'];

$sql = "SELECT * FROM expenses WHERE user_id = '$id' $filter_query ORDER BY $sort_column $sort_order";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
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
    <script language="JavaScript" type="text/javascript">
        function checkDelete() {
            return confirm('Are you sure?');
        }
    </script>
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
        <script>
            function Menu(e) {
                let list = document.querySelector('ul');
                e.name === 'menu' ? (e.name = "close", list.classList.add('top-[80px]'), list.classList.add('opacity-100')) : (e.name = "menu", list.classList.remove('top-[80px]'), list.classList.remove('opacity-100'))
            }
        </script>
    </header>

    <div class="text-center p-4">
        <h1 class="text-white font-bold text-4xl"><span class=" hover:text-red-600 text-6xl">Expense</span> Tracker</h1>
        <h2 class="text-white font-bold mt-10 text-3xl hover:text-[#deb887]">Expenses List Of <span class="text-[#deb887] hover:text-white text-4xl"><?php echo $_SESSION['user_name']; ?></span></h2>
        <div class="expenses-list overflow-x-auto mt-4 md:mt-[50px]">
            <div class="flex flex-col m-3">
                <h1 class="text-white font-bold text-3xl hover:text-red-600">Filter</h1>
                <form action="" method="GET">
                    <div class="flex w-full justify-center mt-2 mr-[510px]">
                        <input type="date" name="date" value="<?= isset($_GET['date']) ? $_GET['date'] : '' ?>" required class="p-2 bg-white/50 rounded-md mb-4 w-40">
                        <select name="status" required class="ml-4 p-2 bg-white/50 rounded-md mb-4 w-40">
                            <option value="">Category</option>
                            <option value="Food & Beverage" <?= isset($_GET['status']) && $_GET['status'] == 'Food & Beverage' ? 'selected' : '' ?>>Food & Beverage</option>
                            <option value="Relaxing" <?= isset($_GET['status']) && $_GET['status'] == 'Relaxing' ? 'selected' : '' ?>>Relaxing</option>
                            <option value="Rent" <?= isset($_GET['status']) && $_GET['status'] == 'Rent' ? 'selected' : '' ?>>Rent</option>
                            <option value="Transport" <?= isset($_GET['status']) && $_GET['status'] == 'Transport' ? 'selected' : '' ?>>Transport</option>
                        </select>
                    </div>
                    <button type="submit" class="text-md bg-white/50 text-black font-[Poppins] duration-500 rounded-md hover:bg-red-700 cursor-pointer mt-1  text-xl p-1 mr-[9px]">Submit</button>
                    <a href="user.php" class="text-md bg-white/50 text-black font-[Poppins] duration-500 p-1  rounded-md hover:bg-red-700 cursor-pointer mt-1 text-xl mr-[18px]">Reset</a>
                </form>
            </div>

            <table class="w-full text-left">
                <thead class="bg-grey-50 border-b-2 border-grey-200">
                    <tr class="bg-black/80 text-white text-2xl">
                        <th class="p-3 font-semibold tracking-wide">
                            <a href="?sort=category&order=<?php echo $next_order; ?>">Category</a>
                        </th>
                        <th class="p-3 font-semibold tracking-wide">
                            <a href="?sort=amount&order=<?php echo $next_order; ?>">Amount</a>
                        </th>
                        <th class="p-3 font-semibold tracking-wide">
                            <a href="?sort=date&order=<?php echo $next_order; ?>">Date</a>
                        </th>
                        <th class="p-3 font-semibold tracking-wide">Description</th>
                        <th class="p-3 font-semibold tracking-wide">Delete</th>
                        <th class="p-3 font-semibold tracking-wide">Update</th>
                    </tr>
                </thead>
                <tbody class="bg-black/80 text-white text-xl">
                    <?php
                    $totalAmount = 0;
                    while ($expense = mysqli_fetch_array($query)) {
                        echo "<tr>";
                        echo "<td class='p-3'>{$expense['category']}</td>";
                        echo "<td class='p-3'>{$expense['amount']}</td>";
                        echo "<td class='p-3'>{$expense['date']}</td>";
                        echo "<td class='p-3'>{$expense['description']}</td>";
                        echo "<td class='p-3'><a href='delete_expense.php?rm=" . $expense['ide'] . "' 
           class='text-red-500 hover:underline'
           onclick=\"return confirm('Are you sure you want to delete this expense?');\">
           Delete
        </a>
      </td>";

                        echo "<td class='p-3'><a href='update_expense.php?edit=" . $expense['ide'] . "' class='text-blue-500 hover:underline'>Update</a></td>";
                        echo "</tr>";
                        $totalAmount += $expense['amount'];
                    }
                    ?>
                </tbody>
                <tfoot class="bg-black/80 text-3xl text-white">
                    <tr>
                        <td class="p-3">Total:</td>
                        <td class="p-3" id="total-amount"><?php echo $totalAmount; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <h1 class="text-center text-[#deb887] font-bold text-3xl m-4 hover:text-red-600">Add Your Expenses</h1>
    <div class="input-section  flex justify-center m-4">
        <form class="text-xl flex flex-col w-full max-w-lg" method="post" action="">
            <label class="text-white font-bold " for="category-select">Category:</label>
            <select id="category-select" name="category" class="p-2 bg-white/50 rounded-md mb-4">
                <option value="Food & Beverage">Food & Beverage</option>
                <option value="Rent">Rent</option>
                <option value="Transport">Transport</option>
                <option value="Relaxing">Relaxing</option>
            </select>

            <label class="text-white font-bold" for="amount-input">Amount:</label>
            <input type="number" id="amount-input" name="amount" class="p-2 bg-white/50 rounded-md mb-4 text-white" placeholder="Enter the Amount" required>

            <label class="text-white font-bold" for="date-input">Date:</label>
            <input type="date" id="date-input" name="date" class="p-2 bg-white/50 rounded-md mb-4" required>

            <label class="text-white font-bold" for="description-input">Description:</label>
            <input type="text" id="description-input" name="description" class="p-2 rounded-md bg-white/50 text-white mb-4" placeholder="Want to describe more">

            <button class="text-2xl bg-blue-900 text-white font-[Poppins] duration-500 p-3 rounded-md hover:bg-red-700 cursor-pointer" type="submit" id="add-btn">Add</button>
        </form>
    </div>
</body>

</html>