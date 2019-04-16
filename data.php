<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cjtrowbridge.com/projects/simple-tree/simple-tree.css">
</head>
<body>
<?php

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
	foreach($M365 as $ID => $Coordinates){
		echo '<p><a href="https://www.google.com/maps/place/'.$Coordinates['latitude'].','.$Coordinates['longitude'].'" target="_blank">link</a></p>';
	}

}

ShowM365s();
