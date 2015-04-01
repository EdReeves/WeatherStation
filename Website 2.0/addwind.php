<?php

/**
* Add (wind) data
*
* Accepts incoming wind data from Arduino, vefifies the sender is legitimate using SHA256 and adds the data to the database.
*/

$mysqli = require(realpath(dirname(__FILE__)).'/init.php');

$Pass = 'XXXXX'; // REMOVED FOR GITHUB.

$avgWind = $_POST['avgWind'];
$Gust = $_POST['Gust'];
$Lull = $_POST['Lull'];
$SHA_Incoming = $_POST['KEY'];

$PassFull = '&avgWind='.$avgWind.'&Gust='.$Gust.'&Lull='.$Lull.'&KEY='.$Pass;
$pass_sha256 = hash('sha256', $PassFull, false);

if (strcmp($pass_sha256, $SHA_Incoming) == 0) {
	mysqli_query($mysqli, "INSERT INTO weatherdata.Wind VALUES (NULL, unix_timestamp(now()), CURDATE(), CURTIME(), $avgWind, $Gust, $Lull)");
}
