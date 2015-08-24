<?php
include 'load_files.php';

$loader = new Loader();

$en = new Encryption();

$all_dirs = array('tablet');

foreach($all_dirs as $dir){
	
	$explored_files = explore_dirs($dir);
	echo encrypt_files($explored_files,true);
	
}

function encrypt_files(array $files,$only_root = false){
	global $en;
	$level = 1;
	if(is_array($files)){
		foreach($files as $file){
			if(is_array($file)){
				if($only_root == false){
					echo 'entering in subdirectory , level was '.$level.'<br/>';
				
					encrypt_files($file);
					$level++;
					echo 'Level is now '.$level.'<br/>';
					
				}else{
					
				}
				
			}else{
				echo 'level is '.$level.' at file '.$file.'</br>';
				echo 'getting file contents of '.$file.' <br/>';
				$file_contents = file_get_contents($file);
				
				echo 'encrypting file '.$file.'<br/>';
				
				$encryption = $en->encrypt($en->encrypt($file_contents));
				
				echo 'enctypted file '.$file.'<br/>';
				echo $encryption.'<br/>';
				
				echo 'adding required contents to file '.$file.'<br/>';
				file_put_contents($file,file_get_contents('sample.php'));
				file_put_contents($file,$encryption,FILE_APPEND);
				
				echo 'finalizing file '.$file.'<br/>';
				file_put_contents($file,file_get_contents('sample_end.php'),FILE_APPEND);
				echo 'file encrypted successfully '.$file.'<br/><br/>';
			}
		}
	}else{
		
	}
}

//function to explore all php files under specified directory

function explore_dirs($directory){
	global $loader;
	$dirs = $loader->get_subdirectories($directory);
	$directories = scandir($directory);
	$php_files = array();
	
	foreach($directories as $dir){
		if($dir == '.' || $dir == '..'){
			//ignore them
		}else{
			if(in_array($dir,$dirs)){
				$php_files[] = explore_dirs($directory.'/'.$dir);
			}else{
				$is_php_file = substr($dir,-4) === '.php';
				if($is_php_file){
					$php_files[] = $directory.'/'.$dir;
				}else{
					//not a valid php file
				}
			}
		}
	}
	return $php_files;
}
?>
