<?php
	global $_urls;
	$_urls = array();

	function register_url($regexp, $callback) {
		global $_urls;
		$_urls[] = array('regexp' => $regexp, 'callback' => $callback);
	}
	
	// -- load modules --
	
	$modules = array();
	if(file_exists("modules"))
		$modules = scandir("modules");
	foreach($modules as $module) {
		if($module == '.' || $module == '..')
			continue;
		if(file_exists("modules/$module/$module" . ".php"))
			include("modules/$module/$module" . ".php");
	}
		
	// -- MAIN --
	
	$script_name = basename($_SERVER['SCRIPT_NAME']);
	$path_info = substr($_SERVER["PATH_INFO"], 1);
	
	foreach($_urls as $url) {
		if(preg_match($url['regexp'], $path_info, $matches)) {
			array_shift($matches);
			call_user_func_array($url['callback'], $matches);
			exit;
		}
	}
?>