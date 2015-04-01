<?php

function dewpoint ($temp, $humidity) {
	$DewPointnumer = (243.12 * (log($humidity / 100) + ((17.62 * $temp) / (243.12 + $temp))));
	$Dewpointdenom = (17.62 - (log($humidity / 100) + ((17.62 * $temp) / (243.12 + $temp))));
	$DewPoint = $DewPointnumer / $Dewpointdenom;
	return $DewPoint;
}

function cloudbase ($temp, $dewpoint) {
	$spread = $temp - $dewpoint;
	$cloudbaseAGL = $spread * 400;
	$Cloudbase = ($cloudbaseAGL * 0.30480) + 180;
	return round($Cloudbase, 2);
}

function windchill ($temp, $windspeed) {
	if ($windspeed < 2.3) {
		$Windchill = $temp;
		return $Windchill;
	}
	$windspeedKMH = $windspeed * 3.6;
	$Windchill = 13.12 + 0.6215 * $temp - 11.37 * pow($windspeedKMH, 0.16) + 0.3965 * $temp * pow($windspeedKMH, 0.16);
	return round($Windchill, 2);
}
