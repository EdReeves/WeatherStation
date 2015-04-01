<?php

/**
 * Update wind lull
 *
 * php -f operations/update-wind-lull.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update wind data
$wind_lull_data = array();
$results = mysqli_query($mysqli, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, Lull FROM `Wind`");
while ($row = mysqli_fetch_array($results)) {
	$wind_lull_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$wind_lull_data = json_encode($wind_lull_data);
file_put_contents(JSON_PATH.'/wind-lull.json', $wind_lull_data);
