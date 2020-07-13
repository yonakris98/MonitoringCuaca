<?php 
	include'detail.php';
    require 'connect.php';
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Demo Grafik Garis</title>
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
	  <canvas id="line-chart"></canvas>
	</div>

    <?php
    $requestID = $_GET['id'];

    $query_suhu = mysqli_query($con, "SELECT suhu, s.waktu, kelembaban_udara, kecepatan_angin
    	FROM suhu s, kecepatan_angin k WHERE s.id_wilayah=$requestID 
    	AND k.id_wilayah= $requestID
    	AND s.waktu >= CURDATE() 
  		AND s.waktu < CURDATE() + INTERVAL 1 DAY
  		AND k.waktu >= CURDATE() 
  		AND k.waktu < CURDATE() + INTERVAL 1 DAY");
    // $query_waktu = mysqli_query($con, "SELECT waktu FROM suhu WHERE id_wilayah=$requestID");

    $dataSuhu = array();
    $dataWaktu = array();
    $dataKelembaban = array();
    $dataKecepatan = array();


    while ($row = mysqli_fetch_array($query_suhu)) {
	   $dataSuhu[] = $row['suhu'];
	   $dataWaktu[] = date('h:i', strtotime($row['waktu']));
	   $dataKelembaban[] = $row['kelembaban_udara'];
	   $dataKecepatan[] = $row['kecepatan_angin'];
	}

     ?>

      	<script  type="text/javascript">

    	var linechart = document.getElementById('line-chart');
        var chart = new Chart(linechart, {
          type: 'line',
          data: {
            labels:  <?php echo json_encode($dataWaktu) ?>,
            datasets: [{
              label: 'Suhu',
              data: <?php echo json_encode($dataSuhu) ?>,
              borderColor: 'rgba(255,0,0,1)',
              backgroundColor: 'transparent',
              borderWidth: 2
            },
            {
              label: 'Kelembaban Udara',
              data: <?php echo json_encode($dataKelembaban) ?>,
              borderColor: 'rgba(0,0,255,1)',
              backgroundColor: 'transparent',
              borderWidth: 2
            },
            {
              label: 'Kecepatan Angin',
              data: <?php echo json_encode($dataKecepatan) ?>,
              borderColor: 'rgba(8,158,25,1)',
              backgroundColor: 'transparent',
              borderWidth: 2
            }]
          }
        });
    	</script>

  </body>
</html>