<?php
class dht11{
  public $link='';
  function __construct($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$idWilayah){
    $this->connect();
    $this->storeInDB($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$idWilayah);
  }

  function connect(){
    $this->link = mysqli_connect('localhost','root','') or die ('Cannot connect to DB');
    mysqli_select_db($this->link,'dbcuaca') or die ('Cannot select DB');
  }
  
  function storeInDB($temperature,$humidity,$rain,$latitude,$longitude,$speedwind,$idWilayah){
    $query_suhu = "insert into suhu set suhu='".$temperature."', kelembaban_udara='".$humidity."',  id_wilayah='".$idWilayah."'";
    $query_hujan="insert into hujan set curah_hujan='".$rain."',  id_wilayah='".$idWilayah."'";
    $query_angin = "insert into kecepatan_angin set kecepatan_angin='".$speedwind."', id_wilayah='".$idWilayah."'";

    $result_suhu = mysqli_query($this->link,$query_suhu) or die ("Err query : " .$query_suhu);
    $result_hujan = mysqli_query($this->link,$query_hujan) or die ("Err query : " .$query_hujan);
    $result_angin = mysqli_query($this->link,$query_angin) or die ("Err query : " .$query_angin);

    if($rain>800){
      $query_wilayah = "UPDATE wilayah SET latitude='".$latitude."', longitude='".$longitude."', cuaca='Cerah' WHERE id_wilayah='".$idWilayah."'";
      $result_wilayah = mysqli_query($this->link,$query_wilayah) or die ("Err query : " .$query_wilayah);;
    }else{
      $query_wilayah = "UPDATE wilayah SET latitude='".$latitude."', longitude='".$longitude."', cuaca='Hujan' WHERE id_wilayah='".$idWilayah."'";
      $result_wilayah = mysqli_query($this->link,$query_wilayah) or die ("Err query : " .$query_wilayah);;
    }
    
  }
}
  if($_GET['temperature'] != '' 
    and $_GET['humidity'] != '' 
    and $_GET['rain'] != '' 
    and $_GET['latitude'] != ''
    and $_GET['longitude'] != '' 
    and $_GET['speedwind'] != '' 
    and $_GET['idWilayah'] != ''){
    $dht11 = new dht11($_GET['temperature'],$_GET['humidity'],$_GET['rain'],$_GET['latitude'],$_GET['longitude'],$_GET['speedwind'],$_GET['idWilayah']);
  }
 
?>