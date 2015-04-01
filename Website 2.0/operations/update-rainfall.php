<?php

/**
 * Update rainfall
 *
 * php -f operations/update-rainfall.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Update rainfall data
$rainfall_data = array();
$results = mysqli_query($mysqli, "SELECT UNIXTIME*1000, SUM(Rainfall) FROM `tempdat2` GROUP BY DATE(`tdate`), HOUR(`ttime`)");
while ($row = mysqli_fetch_array($results)) {
	$rainfall_data[] = array(
		(float) $row[0],
		(float) $row[1]
	);
}
$rainfall_data = json_encode($rainfall_data);
file_put_contents(JSON_PATH.'/rainfall.json', $rainfall_data);
