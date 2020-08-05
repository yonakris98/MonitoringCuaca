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

    $query_suhu = mysqli_query($con, "SELECT kecepatan_angin,waktu
    	FROM kecepatan_angin WHERE id_wilayah=$requestID 
  		AND waktu >= CURDATE() 
  		AND waktu < CURDATE() + INTERVAL 1 DAY");

    $dataWaktu = array();
    $dataKecepatan = array();


    while ($row = mysqli_fetch_array($query_suhu)) {
  	   $dataWaktu[] = date('h:i', strtotime($row['waktu']));
  	   $dataKecepatan[] = $row['kecepatan_angin'];
  	}

     ?>

      	<script  type="text/javascript">

    	var linechart = document.getElementById('line-chart');
        var chart = new Chart(linechart, {
          type: 'line',
          data: {
            labels:  <?php echo json_encode($dataWaktu) ?>,
            datasets: [
            {
              label: 'Kecepatan Angin (m/s)',
              data: <?php echo json_encode($dataKecepatan) ?>,
              borderColor: 'rgba(8,158,25,1)',
              backgroundColor: 'transparent',
              borderWidth: 2
            }]
          },
          options: {
              maintainAspectRatio: false,
              scales: {
                yAxes: [{
                  ticks: {        
                    stepSize: 0.5        
                  }
                }]
              }
          }
        });
    	</script>

  </body>
</html>