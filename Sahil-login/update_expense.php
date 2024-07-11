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
    <link rel="stylesheet" href="css/styleuser.css">
</head>
<body>
    <h1>Update Expense</h1>
    <div class="input-section">
        <form method="post" action="">
            <input type="hidden" name="ide" value="<?php echo $expense['ide']; ?>">
            <label for="category-select">Category:</label>
            <select id="category-select" name="category" required>
                <option value="Food & Beverage" <?php echo ($expense['category'] == 'Food & Beverage') ? 'selected' : ''; ?>>Food & Beverage</option>
                <option value="Rent" <?php echo ($expense['category'] == 'Rent') ? 'selected' : ''; ?>>Rent</option>
                <option value="Transport" <?php echo ($expense['category'] == 'Transport') ? 'selected' : ''; ?>>Transport</option>
                <option value="Relaxing" <?php echo ($expense['category'] == 'Relaxing') ? 'selected' : ''; ?>>Relaxing</option>
            </select>
            <label for="amount-input">Amount:</label>
            <input type="number" id="amount-input" name="amount" value="<?php echo $expense['amount']; ?>" required>
            <label for="date-input">Date:</label>
            <input type="date" id="date-input" name="date" value="<?php echo $expense['date']; ?>" required>
            <label for="description-input">Description:</label>
            <input type="text" id="description-input" name="description" value="<?php echo $expense['description']; ?>">
            <button type="submit" id="update-btn">Update</button>
        </form>
    </div>
</body>
</html>
