<?php
	register_action('help', 'bootstrap_help');
	function bootstrap_help() {
		echo "  bootstrap init <filename>\n";
	}
	
	register_action('commands', 'bootstrap_commands');
	function bootstrap_commands($argv) {
		if($argv[1] != 'bootstrap')
			return;
		if($argv[2] == 'init') {
			file_put_contents($argv[3], <<< 'EOF'
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Bootstrap, from Twitter</title>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<link href="modules/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="modules/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
	</body>
</html>
EOF
			);
		}
	}
?>