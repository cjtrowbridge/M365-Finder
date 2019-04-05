<?php 

include('Birds.php');
$Bird = new Birds();

$Auth = $Bird->Token();

var_dump($Auth);

$Scooters = $Bird->getNearbyScooters('37.7582503','-122.5541942');

var_dump($Scooters);
