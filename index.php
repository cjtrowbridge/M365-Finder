<?php 

if(
  isset($_GET['latitude']) &&
  isset($_GET['longitude'])
){
  $Latitude = $_GET['Latitude'];
  $Longitude = $_GET['Longitude'];
}else{
  $Latitude = '37.7582503';
  $Longitude = '-122.5541942';
}

//TODO cache and retrieve tokens corresponding to particular coordinates
//if(!(file_exists('Token.php'))){
  include('Birds.php');
  $Bird = new Birds($Latitude, $Longitude);
  //$Auth = $Bird->Token();
  //file_put_contents('Token.php','<?php $Token = "'.$Auth.'";');
/*}else{
  include('Token.php');
  include('Birds.php');
  $Bird = new Birds($Latitude, $Longitude, $Token);
}*/

$Scooters = $Bird->getNearbyScooters();

var_dump($Scooters);
