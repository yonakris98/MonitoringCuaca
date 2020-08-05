<?php
class windsensor{
  public $link='';
  function __construct($speedwind,$idWilayah){
    $this->Connect();
    $this->storeInDB($speedwind,$idWilayah);
  }

  function connect(){
    $this->link = mysqli_connect('localhost','root','') or die ('Cannot connect to DB');
    mysqli_select_db($this->link,'dbcuaca') or die ('Cannot select DB');
  }
  
  function storeInDB($speedwind,$idWilayah){
    $query_suhu = "insert into kecepatan_angin set kecepatan_angin='".$speedwind."', id_wilayah='".$idWilayah."'";
    
    $result_suhu = mysqli_query($this->link,$query_suhu) or die ("Err query : " .$query_suhu);
  }
}
  if($_GET['speedwind'] != '' and $_GET['idWilayah'] != ''){
    $windsensor = new windsensor($_GET['speedwind'],$_GET['idWilayah']);
  }

?>