<?php

/**
* Add data
*
* Accepts incoming weather data from Arduino, vefifies the sender is legitimate using SHA256 and adds the data to the database.
*/

$mysqli = require(realpath(dirname(__FILE__)).'/init.php');

$Pass = 'XXXXX'; // REMOVED FOR GITHUB

$winddirection = $_POST['winddirection'];

$temperature = $_POST['temperature'];
$pressure = $_POST['pressure'];
$humidity = $_POST['humidity'];
$rainfall = $_POST['rainfall'];
$SHA_Incoming = $_POST['KEY'];

$PassFull = '&winddirection='.$winddirection.'&temperature='.$temperature.'&pressure='.$pressure.'&humidity='.$humidity.'&rainfall='.$rainfall.'&KEY='.$Pass;
$pass_sha256 = hash('sha256', $PassFull, false);

$winddirectionQuotes = '\''.$winddirection.'\'';

if (strcmp($pass_sha256, $SHA_Incoming) == 0) {
	mysqli_query($mysqli, "INSERT INTO tempdat2 VALUES (NULL, unix_timestamp(now()), CURDATE(), CURTIME(), NULL, $winddirectionQuotes, $temperature, $pressure , $humidity, $rainfall)");
}
