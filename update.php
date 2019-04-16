<?php 
date_default_timezone_set('America/Los_Angeles');

if(
  isset($_GET['latitude']) &&
  isset($_GET['longitude'])
){
  UpdateLocation($_GET['latitude'],$_GET['longitude']);
}elseif(isset($_GET['location'])){
  switch($_GET['location']){
    case 'Castro':
      UpdateLocation('37.760822','-122.435024', $_GET['location']);
      break;
    case 'Mission':
      UpdateLocation('37.759663','-122.414909', $_GET['location']);
      break;
    case 'Berkeley':
      UpdateLocation('37.871454','-122.260274', $_GET['location']);
      break;
    case 'Fremont':
      UpdateLocation('37.544446','-121.988112', $_GET['location']);
      break;
    case 'Lake Merritt':
      UpdateLocation('37.809873','-122.261877', $_GET['location']);
      break;
    case 'Mountain View':
      UpdateLocation('37.386228','-122.08439', $_GET['location']);
      break;
    case 'Palo Alto':
      UpdateLocation('37.441715','-122.143124', $_GET['location']);
      break;
    case 'San Jose':
      UpdateLocation('37.338049','-121.886408', $_GET['location']);
      break;
    case 'Soma':
    default:
      UpdateLocation('37.778248','-122.405501', 'Soma');
      break;
  }
}else{
  UpdateLocation('37.778248','-122.405501', 'Soma');
}

function UpdateLocation($Latitude, $Longitude, $LocationName=false){
  $LastSeenBirds = array();
  include('Birds.php');
  $Bird = new Birds($Latitude, $Longitude);
  $Scooters = $Bird->getNearbyScooters();
  $NewBirds = 0;
  if(!(is_dir('data'))){
    if(mkdir('data')===false){
      die('Failed to create data directory. Check permissions.');
    }
  }
  
  foreach($Scooters['birds'] as $Scooter){
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
    
    //We only want to fetch details if we know it is an m365 or if it might be an m365
    if(
      (count(glob('data/'.$Scooter['id'].'/detail/model.*'))==0) ||
      file_exists('data/'.$Scooter['id'].'/detail/model.m365')
    ){
      $Detail = $Bird->getScooterDetail($Scooter['id']);
      $Data = '<?php if(!(isset($Data))){$Data=array();} $Data[ "'.$Scooter["id"].'" ][ "Detail" ] = '.PHP_EOL.var_export($Detail,true).';'.PHP_EOL;
      file_put_contents($DetailPath,$Data);
      if(!(file_exists('data/'.$Scooter['id'].'/detail/model.m365'))){
        $Model = $Detail['model'];
        $ModelPath = 'data/'.$Scooter['id'].'/detail/model.'.$Model;
        file_put_contents($ModelPath,'');
      }
    }
    
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
  //echo PHP_EOL.'<!--'.$Latitude.','.$Longitude.' (LocationName: '.$LocationName.')'.PHP_EOL;
  //var_dump($Scooters);
  //echo '-->';
  
}
