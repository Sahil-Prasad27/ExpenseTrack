<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit();
}

@include 'config.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Charts</title>
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        .chart-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 50px;
        }

        .chart-box h-500 flex {
            width: 45%;
        }
    </style>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            drawPieChart();
            drawLineChart();
            drawDailyChart();
            drawDailyPieChart();
            drawMonthlyChart();
            drawMonthlyPieChart();
        }

        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Amount'],
                <?php
                $sql = "SELECT * FROM expenses";
                $fire = mysqli_query($conn, $sql);
                while ($result = mysqli_fetch_assoc($fire)) {
                    echo "['" . $result['category'] . "', " . $result['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Your Overall Expenses'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        function drawLineChart() {
            var data = google.visualization.arrayToDataTable([
                ['Category', 'Expenses'],
                <?php
                $query = "SELECT * FROM expenses";
                $res = mysqli_query($conn, $query);
                while ($data = mysqli_fetch_assoc($res)) {
                    echo "['" . $data['category'] . "', " . $data['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Your Overall Expenses in line chart',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }

        function drawDailyChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Expenses'],
                <?php
                $query = "SELECT DATE(date) as date, SUM(amount) as amount FROM expenses GROUP BY DATE(date)";
                $res = mysqli_query($conn, $query);
                while ($data = mysqli_fetch_assoc($res)) {
                    echo "['" . $data['date'] . "', " . $data['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Daily Expenses',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('daily_chart'));
            chart.draw(data, options);
        }

        function drawDailyPieChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Amount'],
                <?php
                $query = "SELECT DATE(date) as date, SUM(amount) as amount FROM expenses GROUP BY DATE(date)";
                $res = mysqli_query($conn, $query);
                while ($data = mysqli_fetch_assoc($res)) {
                    echo "['" . $data['date'] . "', " . $data['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Daily Expenses Distribution'
            };

            var chart = new google.visualization.PieChart(document.getElementById('daily_piechart'));
            chart.draw(data, options);
        }

        function drawMonthlyChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Expenses'],
                <?php
                $query = "SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount FROM expenses GROUP BY DATE_FORMAT(date, '%Y-%m')";
                $res = mysqli_query($conn, $query);
                while ($data = mysqli_fetch_assoc($res)) {
                    echo "['" . $data['month'] . "', " . $data['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Monthly Expenses',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('monthly_chart'));
            chart.draw(data, options);
        }

        function drawMonthlyPieChart() {
            var data = google.visualization.arrayToDataTable([
                ['Month', 'Amount'],
                <?php
                $query = "SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as amount FROM expenses GROUP BY DATE_FORMAT(date, '%Y-%m')";
                $res = mysqli_query($conn, $query);
                while ($data = mysqli_fetch_assoc($res)) {
                    echo "['" . $data['month'] . "', " . $data['amount'] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Monthly Expenses Distribution'
            };

            var chart = new google.visualization.PieChart(document.getElementById('monthly_piechart'));
            chart.draw(data, options);
        }
    </script>

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
                <a href="user.php" class="text-xl hover:text-cyan-500 duration-500">BACK</a>
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
    
    <h2 class="text-white text-center font-bold mt-10 text-3xl hover:text-[#deb887] my-6">Expenses List Of <span class="text-[#deb887] hover:text-white text-4xl"><?php echo $_SESSION['user_name']; ?></span></h2>
    <div class="text-center">
        <h2 class="text-white font-bold text-4xl m-2 mb-5 hover:text-red-600" >Overall Expenses</h2>
        <div class="chart-container ">
            <div id="piechart" class="chart-box  flex "></div>
            <div id="curve_chart" class="chart-box  flex"></div>
        </div>

        <h2 class="text-white font-bold text-4xl m-2 mb-5 hover:text-red-600">Daily Expenses</h2>

        <div class="chart-container">
            <div id="daily_piechart" class="chart-box  flex"></div>
            <div id="daily_chart" class="chart-box  flex"></div>

        </div>

        <h2 class="text-white font-bold text-4xl m-2 mb-3 hover:text-red-600 ">Monthly Expenses</h2>
        <div class="chart-container">

            <div id="monthly_piechart" class="flex"></div>
            <div id="monthly_chart" class=" flex"></div>
        </div>
        
    </div>
</body>

</html>