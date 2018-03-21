<?php

define(CONFIG, parse_ini_file('config.ini', true));

require_once(CONFIG['path']['helpers'] . "/library.php");

autoloader(CONFIG['path']['core']);

$base = new Base(CONFIG);

$base->run();
