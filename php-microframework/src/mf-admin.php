<?php
	// command line usage only 
	if(!$argv)
		exit;
	
	function download($url, $file) {
		echo "Download $url ";
		$remote_file = fopen($url, "r");
		$local_file = fopen($file, "w");
		$cnt = 0;
		do{
			$content = fread($remote_file, 8192);
			if($content)
				fwrite($local_file, $content);
			$cnt++;
			if($cnt % 128 == 0)
				echo ".";
		} while($content);
		fclose($remote_file);
		fclose($local_file);
		echo " done.\n";
	}

	function load_packages() {
		$repo_files = array();
		if(file_exists(".repositories"))
			$repo_files = scandir(".repositories");
		$name_indexed_packages = array();
		foreach($repo_files as $file) {
			if($file == '.' || $file == '..')
				continue;
			$packages = json_decode(file_get_contents(".repositories/" . $file));
			foreach($packages as $package) {
				$name_indexed_packages[$package->name] = $package;
			}
		}
		return $name_indexed_packages;
	}
	
	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
	
	function unzip($zip_file, $dir) {
		echo "Decompress $zip_file ...";
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);
		if ($res === TRUE) {
			$zip->extractTo($dir);
			$zip->close();
			echo " done.\n";
		} else {
			echo " failed.\n";
			exit;
		}
	}
	
	// -- action support --
	
	global $_actions;
	$_actions = array();
	
	function register_action($action, $callback) {
		global $_actions;
		if(!isset($_actions[$action]))
			$_actions[$action] = array();
		$_actions[$action][] = $callback;
	}
	
	function do_action($action, $params = array()) {
		global $_actions;
		if(!isset($_actions[$action]))
			return;
		foreach($_actions[$action] as $callback)
			call_user_func_array($callback, $params);
	}
	
	// -- load modules --
	
	$modules = array();
	if(file_exists("modules"))
		$modules = scandir("modules");
	foreach($modules as $module) {
		if($module == '.' || $module == '..')
			continue;
		if(file_exists("modules/$module/$module" . "-admin.php"))
			include("modules/$module/$module" . "-admin.php");
	}

	// -- MAIN --

	if(count($argv) < 2) {
		echo <<< 'EOF'
mf-admin v0.1

Usage: mf-admin <command> <params>
Available commands:
  update - update package list
  list <pkg filter> - list packages, if package name starts with <pkg filter> 
  install <package> - installs package

EOF;
		do_action('help');
		exit;
	}
	
	if($argv[1] == 'update') {
		$repositories = json_decode(file_get_contents('repositories.json'));
		@mkdir(".repositories");
		foreach($repositories as $url) {
			download($url, ".repositories/" . urlencode($url));
		}
	}
	
	if($argv[1] == 'list') {
		$packages = load_packages();
		foreach($packages as $package) {
			if(isset($argv[2]))
				if(!startsWith($package->name, $argv[2]))
					continue;
			echo $package->name . " - " . $package->description . "\n";
		}
	}
	
	if($argv[1] == 'install') {
		if(!$argv[2]) {
			echo "Invalid package name!";
			exit;
		}
		$packages = load_packages();
		foreach($packages as $package) {
			if($package->name == $argv[2]) {
				$founded_package = $package;
				break;
			}
		}
		if(!$founded_package) {
			echo $argv[2] . " package not found!";
			exit;
		}
		@mkdir("modules");
		download($founded_package->url, "modules/" . $founded_package->name . ".zip");
		@mkdir("modules/" . $founded_package->name);
		unzip("modules/" . $founded_package->name . ".zip", "modules/" . $founded_package->name);
		unlink("modules/" . $founded_package->name . ".zip");
		
		@mkdir("config");
		$install_script = "modules/" . $founded_package->name . '/' . $founded_package->name . '.install.php';
		if(file_exists($install_script)) {
			include($install_script);
			unlink($install_script);
		}
	}
	
	do_action('commands', array($argv));
?>