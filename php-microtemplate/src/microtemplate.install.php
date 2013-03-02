<?php
	$config = <<<EOF
<?php
	define('TEMPLATE_DIR', 'templates');
?>
EOF;

	if(!file_exists('config/microtemplate.conf.php'))
		file_put_contents('config/microtemplate.conf.php', $config);
?>