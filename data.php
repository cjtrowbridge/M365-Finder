<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cjtrowbridge.com/projects/simple-tree/simple-tree.css">
</head>
<body>
<?php

function ShowM365s(){
	if($handle = opendir('data')){
		while(false !== ($entry = readdir($handle))){
			if(is_dir($Root.DIRECTORY_SEPARATOR.$CurrentPath.DIRECTORY_SEPARATOR.$entry)){
				if(($entry !== '.')&& ($entry!=='..')){
					$directories[$entry]=$entry;
				}
			}
		}
		closedir($handle);
	}
	foreach($directories as $name => $directory){
		if(file_exists('data/'.$name.'/model.m365')){
			include('data/'.$name.'/detail.php');
		}
	}
	
	
	echo '<ul>';
	foreach($Data as $ID => $Detail){
		var_dump($Detail);
	}
	echo '</ul>';
}

ShowM365s();
