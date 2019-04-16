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
	foreach($directories as $name => $directory){
		if(file_exists('data/'.$name.'/detail/model.m365')){
			echo '<p><a href="data/'.$name.'/detail/model.m365" target="_blank">'.file_get_contents('data/'.$name.'/detail/model.m365').'</a></p>';
		}
	}

}

ShowM365s();
