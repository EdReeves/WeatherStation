<?php

/**
 * Update humidity
 *
 * php -f operations/update-humidity.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update humidity data
$humidity_data = array();
$results = mysqli_query($mysqli, "SELECT UNIXTIME*1000, Humidity FROM `tempdat2`");
while ($row = mysqli_fetch_array($results)) {
	$humidity_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$humidity_data = json_encode($humidity_data);
file_put_contents(JSON_PATH.'/humidity.json', $humidity_data);
