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
    $conn->close();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="css/styleuser.css">
</head>
<body>
    <a href="logout.php" class="btn">Logout</a>
    <a href="chart.php" class="btn">View chart</a>
    <h1>Expense Tracker</h1>

    <div class="expenses-list">
        <h2>Expenses List Of <span><?php echo $_SESSION['user_name']; ?></span></h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="expense-table-body">
                <?php
                $user = $_SESSION['user_name'];
                $query = mysqli_query($conn, "SELECT * FROM user WHERE email = '$user'");
                $row = mysqli_fetch_array($query);
                $id = $row['id'];
                
                $query = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id = '$id'");
                $totalAmount = 0;
                while ($expense = mysqli_fetch_array($query)) {
                    echo "<tr>";
                    echo "<td>{$expense['category']}</td>";
                    echo "<td>{$expense['amount']}</td>";
                    echo "<td>{$expense['date']}</td>";
                    echo "<td>{$expense['description']}</td>";
                    echo "<td><a href='delete_expense.php?rm=".$expense['ide']."' class='btn'>Delete</a></td>";  
                    echo "<td><a href='update_expense.php?edit=".$expense['ide']."' class='btn'>Update</a></td>";
                    echo "</tr>";
                    $totalAmount += $expense['amount'];
                }
                
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total:</td>
                    <td id="total-amount"><?php echo $totalAmount; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <h1>Add Your Expenses</h1>
    <div class="input-section">
        <form method="post" action="">
            <label for="category-select">Category:</label>
            <select id="category-select" name="category">
                <option value="Food & Beverage">Food & Beverage</option>
                <option value="Rent">Rent</option>
                <option value="Transport">Transport</option>
                <option value="Relaxing">Relaxing</option>
            </select>
            <label for="amount-input">Amount:</label>
            <input type="number" id="amount-input" name="amount" placeholder="Enter the Amount" required>
            <label for="date-input">Date:</label>
            <input type="date" id="date-input" name="date" required>
            <label for="description-input">Description:</label>
            <input type="text" id="description-input" name="description" placeholder="Want to describe more">
            <button type="submit" id="add-btn">Add</button>
        </form>
    </div>
    <html>
</body>
</html>
