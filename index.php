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
</head>

<body style="font-family:serif;">
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

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-primary">
    <span class="navbar-text">
      <img src="Asset/logo-ukdw.png" >
    </span>
    <span class="navbar-text">
      Monitoring Cuaca Lokal Yogyakarta
    </span>
    <span class="navbar-text">
      <p id="date" style="text-align:right;"></p>
      <a href="https://drive.google.com/file/d/1RrA6Emgif0kLL6-lsF3kDk06qF7fuD4U/view?usp=sharing" target="blank">Download code dan rangkaian</a>
    </span>
  </nav>

  <br><br>

  <!-- Maps -->
   <div id="map">
    <script>
      // Initialize and add the map
      function initMap() {

        // Variabel untuk menyimpan informasi lokasi
        var infoWindow = new google.maps.InfoWindow;
        // Pembuatan peta
        var peta = new google.maps.Map(document.getElementById('map'), { zoom: 10, center: { lat:-7.786119, lng: 110.378256} });      
        // Variabel untuk menyimpan batas kordinat
        var bounds = new google.maps.LatLngBounds();

        <?php
            $sql = "SELECT * FROM wilayah";
            $result = mysqli_query($con,$sql) or die("gagal select"); 
            while ($row = mysqli_fetch_assoc($result)) {
              $nama = $row["nama"];
              $cuaca = $row["cuaca"];
              $lat  = $row["latitude"];
              $long = $row["longitude"];
              echo "addMarker($lat, $long, '$nama', '$cuaca');\n";
            }
        ?> 

         // Proses membuat marker 
        function addMarker(lat, lng, info, info_cuaca){

            var lokasi = new google.maps.LatLng(lat, lng);
            bounds.extend(lokasi);
            if(info_cuaca=='Cerah'){
              var marker = new google.maps.Marker({
                map: peta,
                position: lokasi,
                icon: 'Asset/marker_cerah.png'
              }); 
            }else{
              var marker = new google.maps.Marker({
                map: peta,
                position: lokasi,
                icon: 'Asset/marker_hujan.png'
              }); 
            }
            peta.fitBounds(bounds);
            bindInfoWindow(marker, peta, infoWindow, info, info_cuaca);
         }
        //Menampilkan informasi pada masing-masing marker yang diklik
        function bindInfoWindow(marker, peta, infoWindow, html, html_cuaca){
            google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html+'</br>'+html_cuaca);
            infoWindow.open(peta, marker);
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL0u4PRWjSou-ykkgjdvHriLXvm1RUjfY&callback=initMap"></script>
  </div>

  <br><br>

  <!-- Table -->
<table class="table table-borderless">
<tr>
  <!-- Table Left -->
  <td>
    <table class="table table-bordered table-hover table-striped">
      <colgroup>
        <col class="col-xs-1">
        <col class="col-xs-7">
      </colgroup>
      <thead>
        <tr>
          <th>WILAYAH</th>
          <th>CUACA</th>
          <th>TERAKHIR DIUPDATE PADA</th>
          <th>LOKASI</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM wilayah";
          $result = mysqli_query($con,$sql) or die("gagal select"); 
          while($row = mysqli_fetch_assoc($result)) {
            echo'<tr>';
            echo '<td>'.$row['nama'].'</td>';
            echo '<td>'.$row['cuaca'].'</td>';
            echo '<td>'.$row['waktu'].'</td>';
            echo '<td>'.'<a href="https://www.google.com/maps/place/'.$row['latitude'].",".$row['longitude'].'" target="_blank">Google Maps</a>'.'</td>';
            echo'</tr>';
          }
        ?>
        </tr>
        </tbody>
    </table>
  </td>
  <!-- Table Right -->
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