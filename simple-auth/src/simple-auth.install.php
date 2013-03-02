<?php
	$config = <<<EOF
<?php
	define('LOGIN_URL', '');
?>
EOF;

	if(!file_exists('config/simple-auth.conf.php'))
		file_put_contents('config/simple-auth.conf.php', $config);
?>