<?php 

if(
  isset($_GET['latitude']) &&
  isset($_GET['longitude'])
){
  $Latitude = $_GET['latitude'];
  $Longitude = $_GET['longitude'];
}else{
  $Latitude = '37.7582503';
  $Longitude = '-122.5541942';
}

include('Birds.php');
$Bird = new Birds($Latitude, $Longitude);

$Scooters = $Bird->getNearbyScooters();

var_dump($Scooters);
