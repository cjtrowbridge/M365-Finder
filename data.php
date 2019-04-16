<!DOCTYPE html>
<head>
  <link rel="stylesheet" href="https://cjtrowbridge.com/projects/simple-tree/simple-tree.css">
</head>
<body>
<?php

function ShowDirectoryTree($Root = '.',$CurrentPath=''){
	$DirectoriesNotToExpandByDefault=array(
		'.git'
	);
	$Root = rtrim($Root,"/");
	$CurrentPath = trim($CurrentPath,"/");
		
	$directories=array();
	$files=array();
	echo '<ul class="tree">';
	if($handle = opendir($Root.DIRECTORY_SEPARATOR.$CurrentPath)){
		while(false !== ($entry = readdir($handle))){
			if(is_dir($Root.DIRECTORY_SEPARATOR.$CurrentPath.DIRECTORY_SEPARATOR.$entry)){
				if(($entry !== '.')&& ($entry!=='..')){
					$directories[$entry]=$entry;
				}
			}else{
				$files[$entry]=$entry;
			}
		}
		closedir($handle);
	}
	asort($directories);
	rsort($files);
	foreach($directories as $name => $directory){
		echo '<li><a href="'.$name.'"><img src="/icons/folder.gif" alt="[DIR]"> '.$name.'</a>';
		
		$Skip = false;
		foreach($DirectoriesNotToExpandByDefault as $Ignore){
			if( strpos(strtolower($name),strtolower($Ignore) ) !== false){
				$Skip = true;
			}
		}
		if(!($Skip)){
			$RecursivePath=$CurrentPath.DIRECTORY_SEPARATOR.$name;
			ShowDirectoryTree($Root,$RecursivePath);
		}
		echo '</li>';
	}
	foreach($files as $name => $file){
		echo '<li><a href="'.$CurrentPath.DIRECTORY_SEPARATOR.$name.'"><img src="/icons/unknown.gif" alt="[DIR]"> '.$name.'</a></li>';
	}
	echo '</ul>';
}

ShowDirectoryTree('raw');
