<?php

/**
 * Update wind direction
 *
 * php -f operations/update-wind-direction.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

// Find the total number of rows.
$result = mysqli_query($mysqli, "SELECT ID from tempdat2");
$totalrows = mysqli_num_rows($result);

$wind_direction_data = array();

// Iterate through and output the percentages for each speed range in each direction.
for ($i = 0; $i <= 8; $i += 2) {

	$result = mysqli_query($mysqli, "SELECT COUNT(WindSpeed) as WindSpeed, `WindDirection` FROM `tempdat2` WHERE `WindSpeed` > $i AND `WindSpeed` <= $i+2 GROUP BY `WindDirection` ORDER BY WindDirection ASC");
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$wind_direction_data[trim($row['WindDirection'])][($i.'-'.($i + 2))] = round((($row['WindSpeed'] / $totalrows) * 100), 2);
	}

}

// Windspeeds >10m/s
$result = mysqli_query($mysqli, "SELECT COUNT(WindSpeed) as WindSpeed, `WindDirection` FROM `tempdat2` WHERE `WindSpeed` > 10  GROUP BY `WindDirection`ORDER BY WindDirection ASC");
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$wind_direction_data[trim($row['WindDirection'])]['10+'] = round((($row['WindSpeed'] / $totalrows) * 100), 2);
}

$wind_direction_data = array(
	'N' => $wind_direction_data['N'],
	'NE' => $wind_direction_data['NE'],
	'E' => $wind_direction_data['E'],
	'SE' => $wind_direction_data['SE'],
	'S' => $wind_direction_data['S'],
	'SW' => $wind_direction_data['SW'],
	'W' => $wind_direction_data['W'],
	'NW' => $wind_direction_data['NW']
);

$wind_direction_data = json_encode($wind_direction_data);
file_put_contents(JSON_PATH.'/wind-direction.json', $wind_direction_data);
