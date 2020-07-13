<?php
  session_start();
  require 'connect.php';
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <title></title>
  <style type="text/css">
      .container {
        width: 100%;
        margin: 15px 10px;
      }

      .chart {
        width: 50%;
        float: left;
        text-align: center;
      }
  </style>
</head>

<body style="font-family:serif">
  <!-- INCLUDE SCRIPT -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script src="js/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="js/linegraph.js"></script>
  

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-primary"> 
    <span class="navbar-text">
    <a href="#" id="index" onclick="document.location=this.id+'.php';return false;" >
      <img src="Asset/logo-ukdw.png" >
    </a>
    </span>
    <span class="navbar-text">
      Monitoring Cuaca Lokal Yogyakarta
    </span>
    <span class="navbar-text">
      <p id="date"  style="text-align:right;"></p>
      <a href="https://drive.google.com/file/d/1RrA6Emgif0kLL6-lsF3kDk06qF7fuD4U/view?usp=sharing" target="blank">Download code dan rangkaian</a>
    </span>
  </nav>

  <br><br>

  <!-- INFO CUACA -->
  <table class="table table-borderless">
  <tr>
    <td>
      <div style="text-align:center; font-size:28px;">
      <table align="center" style="border: 4px solid black;" cellpadding="20"><tr><td>
      <?php
          $get_id = $_GET['id'];

          // $sql = "SELECT * FROM(wilayah LEFT JOIN suhu USING (id_wilayah) LEFT JOIN hujan USING (id_wilayah) LEFT JOIN  kecepatan_angin USING (id_wilayah)) WHERE id_wilayah=$get_id";

          $sql = "SELECT nama,suhu,kelembaban_udara,curah_hujan,kecepatan_angin
          FROM wilayah,suhu,hujan,kecepatan_angin
          WHERE wilayah.id_wilayah=$get_id
          AND suhu.id_suhu=(SELECT MAX(id_suhu) FROM suhu) 
          AND hujan.id_hujan=(SELECT MAX(id_hujan) FROM hujan) 
          AND kecepatan_angin.id_angin=(SELECT MAX(id_angin)FROM kecepatan_angin)
          AND suhu.id_wilayah=$get_id
          AND hujan.id_wilayah=$get_id
          AND kecepatan_angin.id_wilayah=$get_id";

          $result = mysqli_query($con,$sql) or die(mysqli_error($con)); 
          $row = mysqli_fetch_assoc($result);

          if($row['curah_hujan'] > 500){
            echo '<p>'.$row['nama'].'</p>';
            echo "Cerah <br>"; 
            $sqlFoto = "SELECT *  FROM asset WHERE id = 1";
            $resultFoto = mysqli_query($con,$sqlFoto) or die("gagal select"); 
            $rowFoto = mysqli_fetch_assoc($resultFoto);
            echo '<img src="'.$rowFoto['foto'].'" style="width:250px; height:250px";>';
          }else{
            echo '<p>'.$row['nama'].'</p>';
            echo "Hujan <br>";
            $sqlFoto = "SELECT *  FROM asset WHERE id = 2";
            $resultFoto = mysqli_query($con,$sqlFoto) or die("gagal select"); 
            $rowFoto = mysqli_fetch_assoc($resultFoto);
            echo '<img src="'.$rowFoto['foto'].'" style="width:250px; height:250px";>';
          }
          echo '<p>'.$row['suhu'].'‎°C</p>';
          echo '<p>'.$row['kecepatan_angin'].' m/s</p>';
      ?>
      </td></tr>
      </table>
      </div>
    </td>
    
    <!-- RIGHT -->
    <td>
    <table>
       <b>Daftar Daerah</b>
            <?php
              $sql = "SELECT * FROM wilayah";
              $result = mysqli_query($con,$sql) or die("gagal select"); 
              while($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td><a href="detail.php?id='.$row['id_wilayah'].'">'.$row['nama'].'</a></td>';
                echo '</tr>';
              }
            ?>
      </table>
    </td>
  </tr>
  </table>
  <!-- INFO CUACA -->
  
  <!-- Chart -->
  <div style="padding-left: 10px;">
    <?php
      $get_id = $_GET['id'];
      echo("<button class=\"btn btn-primary\" onclick=\"location.href='chart.php?id='+$get_id\">Tampilkan Chart</button>");
    ?>
  </div>
  <!-- Date -->
  <script>
    n =  new Date();
    y = n.getFullYear();
    m = n.getMonth() + 1;
    d = n.getDate();
    document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
  </script>
</body>

</html>
