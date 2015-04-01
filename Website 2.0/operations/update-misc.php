<?php

/**
 * Update misc
 *
 * php -f operations/update-misc.php
 */

$mysqli = require(realpath(dirname(dirname(__FILE__))).'/init.php');

$misc_data = array();

$results = mysqli_query($mysqli, "SELECT * FROM `tempdat2` ORDER BY ID DESC LIMIT 1");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['id'] = (int) $row['ID'];
$misc_data['time'] = (int) $row['UNIXTIME'];
$misc_data['last-reading'] = $row['ttime'];
$misc_data['current-wind-direction'] = $row['WindDirection'];
$misc_data['current-temperature'] = (float) $row['temperature'];
$misc_data['current-pressure'] = (float) $row['Pressure'];
$misc_data['current-humidity'] = (float) $row['Humidity'];

$results = mysqli_query($mysqli, "SELECT AVGwind FROM `Wind` ORDER BY ID DESC LIMIT 1");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['current-wind-average'] = round($row['AVGwind'], 2);

$results = mysqli_query($mysqli, "SELECT SUM(Rainfall) as rainfall FROM `tempdat2` WHERE `tdate`= CURDATE()");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['todays-rainfall'] = round($row['rainfall'], 2);

$results = mysqli_query($mysqli, "SELECT DATEDIFF(CURDATE(),'2014-10-25') AS DiffDate");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['uptime'] = (float) $row['DiffDate'];

$results = mysqli_query($mysqli, "SELECT ROUND(AVG(temperature),2) as Day_AVG FROM `tempdat2` WHERE DATE(tdate) = DATE(NOW())");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['todays-temperature-average'] = (float) $row['Day_AVG'];

$results = mysqli_query($mysqli, "SELECT temperature, ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature DESC LIMIT 1");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['todays-temperature-high']['value'] = (float) $row['temperature'];
$misc_data['todays-temperature-high']['time'] = $row['ttime'];

$results = mysqli_query($mysqli, "SELECT temperature, ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature ASC LIMIT 1");
$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
$misc_data['todays-temperature-low']['value'] = (float) $row['temperature'];
$misc_data['todays-temperature-low']['time'] = $row['ttime'];

$misc_data['sunrise'] = date_sunrise(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);
$misc_data['sunset'] = date_sunset(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);

$misc_data['current-dew-point'] = round(dewpoint($misc_data['current-temperature'], $misc_data['current-humidity']), 2);
$misc_data['current-cloud-base'] = cloudbase($misc_data['current-temperature'], $misc_data['current-dew-point']);
$misc_data['current-wind-chill'] = windchill($misc_data['current-temperature'], $misc_data['current-wind-average']);

$misc_data = json_encode($misc_data);
file_put_contents(JSON_PATH.'/misc.json', $misc_data);
