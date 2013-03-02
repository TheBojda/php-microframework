<?php
	$config = <<<EOF
<?php
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', '');
?>
EOF;

	if(!file_exists('config/adodb-lite.conf.php'))
		file_put_contents('config/adodb-lite.conf.php', $config);
?>