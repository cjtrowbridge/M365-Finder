<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cjtrowbridge.com/projects/simple-tree/simple-tree.css">
</head>
<body>
<?php

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}

//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
	
function ShowM365s(){
	if($handle = opendir('data')){
		while(false !== ($entry = readdir($handle))){
			if(is_dir('data/'.$entry)){
				if(($entry !== '.')&& ($entry!=='..')){
					$directories[$entry]=$entry;
				}
			}
		}
		closedir($handle);
	}
	global $M365;
	$M365=array();
	foreach($directories as $name => $directory){
		if(file_exists('data/'.$name.'/detail/model.m365')){
			include('data/'.$name.'/detail/model.m365');
		}
	}
	$Sorted = array();
	foreach($M365 as $ID => $Coordinates){
		$Distance = distance($Coordinates['latitude'], $Coordinates['longitude'], $_GET['latitude'], $_GET['longitude'], "M")
		$Sorted[ $Distance ] = $Coordinates;
	}
	ksort($Sorted);
	foreach($Sorted as $Distance => $Coordinates){
		echo '<p><a href="https://www.google.com/maps/place/'.$Coordinates['latitude'].','.$Coordinates['longitude'].'" target="_blank">M365 Last Seen '.$Distance.' miles away.</a></p>';
	}

}

ShowM365s();
