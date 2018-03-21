<?php

/**
 * Show vars in pre tags
 * @param mix $data
 * @return string
 */
function var_info($data) {
	echo "<pre>";
	var_dump($data); 
	echo "</pre>"; 
}

/**
 * Autoloader
 * @param string $path 
 */
function autoloader($path) {
	if( is_dir($path) ) {
		$resources = scandir($path);
		
		foreach ($resources as $res) {
			if( is_file($path . '/' . $res) ) {
				include_once $path . '/' . $res;
			}
		}

	} else {
		die($path . " is not a directory!");
	}
}