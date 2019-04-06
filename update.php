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

$NewBirds = 0;

foreach($Scooters['birds'] as $Scooter){
  if(!(is_dir('data'))){
    if(mkdir('data')===false){
      die('Failed to create data directory. Check permissions.');
    }
  }
  if(!(is_dir('data/'.$Scooter['id']))){
    mkdir('data/'.$Scooter['id']);
    $NewBirds++;
  }
  if(!(is_dir('data/'.$Scooter['id'].'/'.date('Y-m-d')))){
    mkdir('data/'.$Scooter['id'].'/'.date('Y-m-d'));
  }
  
  $Path = 'data/'.$Scooter['id'].'/'.date('Y-m-d').'/'.date('H:i:s').'.php';
  $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "'.date("Y-m-d").'" ][ "'.date("H:i:s").'" ] = '.PHP_EOL.var_export($Scooter,true).';';
  file_put_contents($Path,$Data);
  
  
  $Scooter = $Scooters['birds'][0];
  if(!(is_dir('data/'.$Scooter['id'].'/detail'))){
    mkdir('data/'.$Scooter['id'].'/detail');
  }
  $DetailPath = 'data/'.$Scooter['id'].'/detail/last.php';
  $Detail = $Bird->getScooterDetail($Scooter['id']);
  $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "Detail" ] = '.PHP_EOL.var_export($Detail,true).';';
  file_put_contents($DetailPath,$Data);
  //echo '<p>Fetched '.count($Detail).' details for <a href="'.$DetailPath.'" target="_blank">'.$Scooter["id"].'</a></p>';
  
}


$BirdsFound = count($Scooters['birds']);
if($BirdsFound==0){
  echo 'Something went wrong. No birds found.';
}else{
  echo 'Updated '.$BirdsFound.' birds. ('.$NewBirds.' new.)';
}

echo '<!--'.PHP_EOL;
var_dump($Scooters);
echo PHP_EOL.'-->';
