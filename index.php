<?php 

include('Birds.php');
$Bird = new Birds();

$Scooters = $Bird->getNearbyScooters('37.7582503','-122.5541942');

var_dump($Scooters);
