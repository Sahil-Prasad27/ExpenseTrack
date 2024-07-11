<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit();
}

@include 'config.php';
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Category', 'Amount'],
          <?php
          $sql ="SELECT * FROM expenses";
          $fire = mysqli_query($conn,$sql);
          while($result = mysqli_fetch_assoc($fire)){
            echo"['".$result['Category']."',".$result['amount']."],";
          }
          ?>
          ]);

        var options = {
          title: 'Your Overall expenses'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],

          <?php
            $query="select * from expenses";
            $res=mysqli_query($conn,$query);
            while($data=mysqli_fetch_array($res)){
                $category = $data['category'];
                $amount = $data['amount'];
          ?>
          ['<?php echo $category;?>',<?php echo $amount;?>],
          <?php 
            }
          ?>
          
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>

