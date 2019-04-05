<?php 

if(!(file_exists('Token.php'))){
  include('Birds.php');
  $Bird = new Birds();
  $Auth = $Bird->Token();
  file_put_contents('Token.php','<?php $Token = "'.$Auth.'";');
}else{
  include('Token.php');
  include('Birds.php');
  $Bird = new Birds($Token);
}

$Scooters = $Bird->getNearbyScooters('37.7582503','-122.5541942');

var_dump($Scooters);
