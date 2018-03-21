<?php
date_default_timezone_set('Europe/Kiev');
define(CONFIG, parse_ini_file('config.ini', true));	
require_once(CONFIG['path']['helpers'] . "/library.php");

/**
 * Test results of app
 */
$date_start = new DateTime($_POST['date_start']);
$date_end = new DateTime($_POST['date_end']);
$interval = $date_start->diff($date_end);

$data = [
	'status' 			=> true,
	'message' 		=> $interval->format('%y лет %m месяцев %d дней'),
	'total_days' 	=> $interval->format('%a'),
	'years' 			=> null,
	'months' 			=> null,
	'days' 				=> null,
	'invert' 			=> null
];

echo json_encode($data);
