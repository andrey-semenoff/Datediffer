<?php
$config = parse_ini_file('config.ini', true);

require_once(__DIR__ . $config['path']['helpers'] . "/library.php");

autoloader(__DIR__ . $config['path']['core']);

$base = new Base($config);

$base->run();
