<?php
  require 'connect.php';
  include 'detail.php';

  //database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "dbcuaca";
  $requestID = $_GET['id'];

  //get connection
  $mysqli = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($mysqli->connect_error) {
      die("Connection failed: " . $mysqli->connect_error);
  }

  $query_suhu = " SELECT suhu FROM suhu WHERE id_wilayah= $requestID AND waktu >= CURDATE() AND waktu < CURDATE() + INTERVAL 1 DAY";

  $query_kelembaban = "SELECT kelembaban_udara FROM suhu WHERE id_wilayah= $requestID
  AND waktu >= CURDATE() 
  AND waktu < CURDATE() + INTERVAL 1 DAY";

  $query_udara = "SELECT kecepatan_angin FROM kecepatan_angin WHERE id_wilayah= $requestID
  AND waktu >= CURDATE() 
  AND waktu < CURDATE() + INTERVAL 1 DAY";

  $query_waktu = "SELECT waktu FROM suhu WHERE id_wilayah= $requestID
  AND waktu >= CURDATE() 
  AND waktu < CURDATE() + INTERVAL 1 DAY";

  //execute query
  $result_suhu = mysqli_query($con,$query_suhu) 
  or die(mysqli_error($con));
  $result_kelembaban = mysqli_query($con,$query_kelembaban) 
  or die(mysqli_error($con));
  $result_udara = mysqli_query($con,$query_udara) 
  or die(mysqli_error($con));
  $result_waktu = mysqli_query($con,$query_waktu) 
  or die(mysqli_error($con));


  //loop through the returned data
  foreach ($result_suhu as $row) {
   $dataSuhu[] = $row;
  }
  foreach ($result_kelembaban as $row) {
   $dataKelembaban[] = $row;
  }
  foreach ($result_udara as $row) {
   $dataUdara[] = $row;
  }
  foreach ($result_waktu as $row) {
   $dataWaktu[] = $row;
  }
  
  //free memory associated with result
  $result->close();

  //close connection
  $mysqli->close();

  //json_encode($data);

  //now print the data
  echo json_encode($dataSuhu);
  echo json_encode($dataKelembaban);
  echo json_encode($dataUdara);
  echo json_encode($dataWaktu);
?>
