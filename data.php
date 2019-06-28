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

function ago($time){
  /*
    Ago accepts any date or time and returns a string explaining how long ago that was.
    For example, "6 days ago" or "8 seconds ago"
  */
  $Original = $time;
  if(is_int($time)===false){
    $time=strtotime($time);
  }
  if(($time==0)||(empty($time))){
    return 'Never';
  }
  $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
  $lengths = array("60","60","24","7","4.35","12","10");
  $now = time();
  $difference     = $now - $time;
  $tense         = "ago";
  for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
    $difference /= $lengths[$j];
  }
  $difference = round($difference);
  if($difference != 1) {
    $periods[$j].= "s";
  }
  return "$difference $periods[$j] ago";
}

	
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
			$File = 'data/'.$name.'/detail/model.m365';
			include($File);
			$M365[$name]['time']=filemtime($File);
			$M365[$name]['id']=$name;
		}
	}
	$Sorted = array();
	foreach($M365 as $ID => $Coordinates){
		if(
			isset($Coordinates['latitude']) &&
			isset($Coordinates['longitude'])
		){
			$Distance = distance($Coordinates['latitude'], $Coordinates['longitude'], $_GET['latitude'], $_GET['longitude'], "M");
			$Sorted[ $Distance ] = $Coordinates;
		}else{
			echo "<!--\nSomething Wrong with this Bird;\n";
			var_dump($Coordinates);
			echo "-->\n";
		}
	}
	ksort($Sorted);
	foreach($Sorted as $Distance => $Coordinates){
		echo '<p>M365 Last Seen <a href="https://www.google.com/maps/place/'.$Coordinates['latitude'].','.$Coordinates['longitude'].'" target="_blank">'.$Distance.' miles away</a>, <a href="data/'.$Coordinates['id'].'/detail/last.json" target="_blank">'.ago($Coordinates['time']).'.</a></p>';
	}

}

ShowM365s();
