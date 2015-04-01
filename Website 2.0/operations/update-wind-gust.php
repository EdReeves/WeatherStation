<?php

/**
 * Update wind gust
 *
 * php -f operations/update-wind-gust.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update wind data
$wind_gust_data = array();
$results = mysqli_query($mysqli, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, Gust FROM `Wind`");
while ($row = mysqli_fetch_array($results)) {
	$wind_gust_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$wind_gust_data = json_encode($wind_gust_data);
file_put_contents(JSON_PATH.'/wind-gust.json', $wind_gust_data);
