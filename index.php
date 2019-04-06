<?php 

date_default_timezone_set('America/Los_Angeles');

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

foreach($Scooters['birds'] as $Scooter){
  if(!(is_dir('data'))){
    if(mkdir('data')===false){
      die('Failed to create data directory. Check permissions.');
    }
  }
  if(!(is_dir('data/'.$Scooter['id']))){
    mkdir('data/'.$Scooter['id']);
  }
  if(!(is_dir('data/'.$Scooter['id'].'/'.date('Y-m-d')))){
    mkdir('data/'.$Scooter['id'].'/'.date('Y-m-d'));
  }
  
  $Path = 'data/'.$Scooter['id'].'/'.date('Y-m-d').'/'.date('H:i:s').'.php';
  $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "'.date("Y-m-d").'" ][ "'.date("H:i:s").'" ] = '.PHP_EOL.var_export($Scooter,true).';';
  file_put_contents($Path,$Data);
  
  echo '<p>exported: <a href="'.$Path.'" target="_blank">'.$Path.'</a></p>';
  
  //$Path = $Scooter['id'].'/details.php';
  
}
$BirdsFound = count($Scooters['birds']);
if($BirdsFound==0){
  echo '<p>Something went wrong. No birds found.</p>';
}else{
  echo '<p>Found '.$BirdsFound.' birds.</p>';
}

echo '<!--'.PHP_EOL;
var_dump($Scooters);
echo PHP_EOL.'-->';
