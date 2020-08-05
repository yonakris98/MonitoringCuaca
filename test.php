<?php 
  require 'connect.php';
?>

<html>
  <head>
  </head>
  <body>
    <?php
        // $namaWilayah = "AAAAA";
        // $result = mysqli_query($con, "SELECT * FROM wilayah WHERE nama='$namaWilayah' LIMIT 1");

        // if(mysqli_fetch_array($result) == true){
        //   echo 'ada';
        // }
        // else{
        //   echo 'ga ada';
        // }
        $dataSuhu = array();

        $query_idWilayah = mysqli_query($con, "SELECT id_wilayah FROM wilayah WHERE nama='Klitren' LIMIT 1");

        while ($result_idWilayah = mysqli_fetch_array($query_idWilayah)) {
           $dataSuhu[] = $result_idWilayah['id_wilayah'];
        }
        $dataSuhu = mysqli_fetch_array($result_idWilayah);
        echo $dataSuhu;
        // $stringver = implode("",$dataSuhu);
        // echo $stringver;
     ?>

  </body>
</html>