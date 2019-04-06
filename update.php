<?php 
date_default_timezone_set('America/Los_Angeles');

if(
  isset($_GET['latitude']) &&
  isset($_GET['longitude'])
){
  UpdateLocation($_GET['latitude'],$_GET['longitude']);
}elseif(issset($_GET['location'])){
  switch($_GET['location']){
    case 'Soma':
      UpdateLocation('37.7782488','-122.405501', $_GET['location']);
      break;
    case 'Castro':
      UpdateLocation('7.7608221','-122.4350248', $_GET['location']);
      break;
    case 'Mission':
      UpdateLocation('37.7596636','-122.4149094', $_GET['location']);
      break;
    case 'Berkeley':
      UpdateLocation('37.8714545','-122.2602743', $_GET['location']);
      break;
    case 'Fremont':
      UpdateLocation('37.5444461','-121.9881122', $_GET['location']);
      break;
    case 'Lake Merritt':
      UpdateLocation('37.8004072','-122.2575443', $_GET['location']);
      break;
    case 'Mountain View':
      UpdateLocation('37.3862282','-122.084398', $_GET['location']);
      break;
    case 'Palo Alto':
      UpdateLocation('37.4417158','-122.1431246', $_GET['location']);
      break;
    case 'San Jose':
      UpdateLocation('37.3380498','-121.8864084', $_GET['location']);
      break;
    default:
      die('Invalid Selection');
  }
}else{
  die('Specify a location.');
}

function UpdateLocation($Latitude, $Longitude, $LocationName=false){
  $LastSeenBirds = array();
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
    //Create a version file containing the current data (this way we can look back over time)
    $Path = 'data/'.$Scooter['id'].'/'.date('Y-m-d').'/'.date('H:i:s').'.php';
    $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "'.date("Y-m-d").'" ][ "'.date("H:i:s").'" ] = '.PHP_EOL.var_export($Scooter,true).';';
    file_put_contents($Path,$Data);
    
    //Update the detail file for this scooter to contain only the latest data (this way it's easy to find the lastest data quickly)
    if(!(is_dir('data/'.$Scooter['id'].'/detail'))){
      mkdir('data/'.$Scooter['id'].'/detail');
    }
    $DetailPath = 'data/'.$Scooter['id'].'/detail/last.php';
    $Detail = $Bird->getScooterDetail($Scooter['id']);
    $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "Detail" ] = '.PHP_EOL.var_export($Detail,true).';'.PHP_EOL;
    file_put_contents($DetailPath,$Data);
    
    $LastSeenBirds[$Scooter['id']]=array(
      'id'  => $Scooter['id'],
      'lat' => 'lat',
      'lon' => 'lon'
    );
  }
  
  if(!(is_dir('location'))){
    if(mkdir('location')===false){
      die('Failed to create location directory. Check permissions.');
    }
  }
  
  if($LocationName){
    //Update the list of birds seen when this location was last updated
    $FriendlyLocation = str_replace(' ','_',$LocationName);
    $LocationPath = 'location/'.$FriendlyLocation.'.php';
    $Data = '<?php $LastSeenBirds= '.PHP_EOL.var_export($LastSeenBirds,true).';'.PHP_EOL;
    file_put_contents($LocationPath,$Data);
  }
  
  //Output some text about what was found
  $BirdsFound = count($Scooters['birds']);
  if($BirdsFound==0){
    echo 'Something went wrong. No birds found.';
  }else{
    echo 'Updated '.$BirdsFound.' birds. ('.$NewBirds.' new.)';
  }
  echo PHP_EOL.'<!--'.$Latitude.','.$Longitude.PHP_EOL;
  var_dump($Scooters);
  echo '-->';
  
}
