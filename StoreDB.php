<?php
class dht11{
  public $link='';
  function __construct($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$namaWilayah){
    $this->connect();
    $this->storeInDB($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$namaWilayah);
  }

  function connect(){
    $this->link = mysqli_connect('localhost','root','') or die ('Cannot connect to DB');
    mysqli_select_db($this->link,'dbcuaca') or die ('Cannot select DB');
  }
  
  function storeInDB($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$namaWilayah){
    $query_check = "SELECT * FROM wilayah WHERE nama='$namaWilayah' LIMIT 1";
    $checkWilayah = mysqli_query($this->link, $query_check) or die ("Err query : " .$query_check);

    if(mysqli_fetch_array($checkWilayah) == true){
      // JIKA WILAYAH SUDAH ADA //
      $query_idWilayah = "SELECT id_wilayah FROM wilayah WHERE nama='$namaWilayah' LIMIT 1";
      $result_idWilayah = mysqli_query($this->link,$query_idWilayah) or die ("Err query : " .$query_idWilayah);

      $result_idWilayah = mysqli_fetch_array($result_idWilayah);
      $data_idWilayah[] = $result_idWilayah['id_wilayah'];
      $string_version = implode("",$data_idWilayah);

      $query_suhu = "insert into suhu set suhu='".$temperature."', kelembaban_udara='".$humidity."',  id_wilayah=".$string_version."";
      $query_hujan="insert into hujan set curah_hujan='".$rain."',  id_wilayah=".$string_version."";
      $query_angin = "insert into kecepatan_angin set kecepatan_angin='".$speedwind."', id_wilayah=".$string_version."";

      $result_suhu = mysqli_query($this->link,$query_suhu) or die ("Err query : " .$query_suhu);
      $result_hujan = mysqli_query($this->link,$query_hujan) or die ("Err query : " .$query_hujan);
      $result_angin = mysqli_query($this->link,$query_angin) or die ("Err query : " .$query_angin);

      if($rain>800){
        $query_wilayah = "UPDATE wilayah SET latitude='".$latitude."', longitude='".$longitude."', cuaca='Cerah' WHERE id_wilayah=".$string_version."";
        $result_wilayah = mysqli_query($this->link,$query_wilayah) or die ("Err query : " .$query_wilayah);;
      }else{
        $query_wilayah = "UPDATE wilayah SET latitude='".$latitude."', longitude='".$longitude."', cuaca='Hujan' WHERE id_wilayah=".$row_idWilayah['id_wilayah']."";
        $result_wilayah = mysqli_query($this->link,$query_wilayah) or die ("Err query : " .$query_wilayah);;
      }
    }
    else{
      // JIKA WILAYAH BELUM ADA //
      if($rain>800){
        $query_newWilayah = "insert into wilayah set nama='$namaWilayah', cuaca='Cerah', latitude='".$latitude."',longitude='".$longitude."'";
        $result_newWilayah = mysqli_query($this->link,$query_newWilayah) or die ("Err query : " .$query_newWilayah);

        $query_idWilayah = "SELECT id_wilayah FROM wilayah WHERE nama='$namaWilayah' LIMIT 1";
        $result_idWilayah = mysqli_query($this->link,$query_idWilayah) or die ("Err query : " .$query_idWilayah);

        $result_idWilayah = mysqli_fetch_array($result_idWilayah);
        $data_idWilayah[] = $result_idWilayah['id_wilayah'];
        $string_version = implode("",$data_idWilayah);

        $query_suhu = "insert into suhu set suhu='".$temperature."', kelembaban_udara='".$humidity."',  id_wilayah=".$string_version."";
        $query_hujan="insert into hujan set curah_hujan='".$rain."',  id_wilayah=".$string_version."";
        $query_angin = "insert into kecepatan_angin set kecepatan_angin='".$speedwind."', id_wilayah=".$string_version."";

        $result_suhu = mysqli_query($this->link,$query_suhu) or die ("Err query : " .$query_suhu);
        $result_hujan = mysqli_query($this->link,$query_hujan) or die ("Err query : " .$query_hujan);
        $result_angin = mysqli_query($this->link,$query_angin) or die ("Err query : " .$query_angin);
      }else{
        $query_newWilayah = "insert into wilayah set nama='$namaWilayah', cuaca='Hujan', latitude='".$latitude."',longitude='".$longitude."'";
        $result_newWilayah = mysqli_query($this->link,$query_newWilayah) or die ("Err query : " .$query_newWilayah);

        $query_idWilayah = "SELECT id_wilayah FROM wilayah WHERE nama='$namaWilayah' LIMIT 1";
        $result_idWilayah = mysqli_query($this->link,$query_idWilayah) or die ("Err query : " .$query_idWilayah);

        $result_idWilayah = mysqli_fetch_array($result_idWilayah);
        $data_idWilayah[] = $result_idWilayah['id_wilayah'];
        $string_version = implode("",$data_idWilayah);

        $query_suhu = "insert into suhu set suhu='".$temperature."', kelembaban_udara='".$humidity."',  id_wilayah=".$string_version."";
        $query_hujan="insert into hujan set curah_hujan='".$rain."',  id_wilayah=".$string_version."";
        $query_angin = "insert into kecepatan_angin set kecepatan_angin='".$speedwind."', id_wilayah=".$string_version."";

        $result_suhu = mysqli_query($this->link,$query_suhu) or die ("Err query : " .$query_suhu);
        $result_hujan = mysqli_query($this->link,$query_hujan) or die ("Err query : " .$query_hujan);
        $result_angin = mysqli_query($this->link,$query_angin) or die ("Err query : " .$query_angin);
      }
    }
  }
}
  if($_GET['temperature'] != '' 
    and $_GET['humidity'] != '' 
    and $_GET['rain'] != '' 
    and $_GET['latitude'] != ''
    and $_GET['longitude'] != '' 
    and $_GET['speedwind'] != '' 
    and $_GET['namaWilayah'] != ''){
    $dht11 = new dht11($_GET['temperature'],$_GET['humidity'],$_GET['rain'],$_GET['latitude'],$_GET['longitude'],$_GET['speedwind'],$_GET['namaWilayah']);
  }
 
?>