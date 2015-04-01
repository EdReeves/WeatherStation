<?php

/**
 * Update wind average
 *
 * php -f operations/update-wind-average.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update wind data
$wind_average_data = array();
$results = mysqli_query($mysqli, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, AVGwind FROM `Wind`");
while ($row = mysqli_fetch_array($results)) {
	$wind_average_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$wind_average_data = json_encode($wind_average_data);
file_put_contents(JSON_PATH.'/wind-average.json', $wind_average_data);
