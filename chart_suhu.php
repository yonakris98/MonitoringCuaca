<?php 
	include'detail.php';
  require 'connect.php';
?>

<html>
  <head>
    <meta charset="utf-8">
    <script src="js/Chart.bundle.min.js"></script>
    <style type="text/css">
        .container {
          width: 100%;
          margin: 15px 10px;
        }

        .chart {
          width: 100%;
          float: left;
          text-align: center;
        }
    </style>
  </head>
  <body>

	<div class="chart">
	  <canvas id="line-chart" style="height: 500px;"></canvas>
	</div>

    <?php
    $requestID = $_GET['id'];

    $query_suhu = mysqli_query($con, "SELECT suhu, waktu
    	FROM suhu WHERE id_wilayah=$requestID 
    	AND id_wilayah= $requestID
    	AND waktu >= CURDATE() 
  		AND waktu < CURDATE() + INTERVAL 1 DAY");

    $dataSuhu = array();
    $dataWaktu = array();


    while ($row = mysqli_fetch_array($query_suhu)) {
  	   $dataSuhu[] = $row['suhu'];
  	   $dataWaktu[] = date('h:i', strtotime($row['waktu']));
  	}

     ?>

      	<script  type="text/javascript">

    	var linechart = document.getElementById('line-chart');
        var chart = new Chart(linechart, {
          type: 'line',
          data: {
            labels:  <?php echo json_encode($dataWaktu) ?>,
            datasets: [{
              label: 'Suhu (°C)',
              data: <?php echo json_encode($dataSuhu) ?>,
              borderColor: 'rgba(255,0,0,1)',
              backgroundColor: 'transparent',
              borderWidth: 2
            }]
          },
          options: {
              maintainAspectRatio: false,
              scales: {
                yAxes: [{
                  ticks: {        
                    stepSize: 2        
                  }
                }]
              }
          }
        });
    	</script>

  </body>
</html>