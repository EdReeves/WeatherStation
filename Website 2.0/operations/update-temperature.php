<?php

/**
 * Update pressure
 *
 * php -f operations/update-temperature.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update temperature data
$temperature_data = array();
$results = mysqli_query($mysqli, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`, ' ', `ttime`))*1000, temperature FROM `tempdat2`");
while ($row = mysqli_fetch_array($results)) {
	$temperature_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$temperature_data = json_encode($temperature_data);
file_put_contents(JSON_PATH.'/temperature.json', $temperature_data);
