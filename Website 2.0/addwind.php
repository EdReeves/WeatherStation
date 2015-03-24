<?php
/*
addwind.php  - Ed Reeves 2015 
Accepts incoming wind data from Arduino, vefifies the sender is legitimate using SHA256 and adds the data to the database. 

*/

include 'other/Database_Connection.php';

$Pass = "xxxx"; //REMOVED FOR GITHUB.


$avgWind=$_POST["avgWind"];
$Gust=$_POST["Gust"];
$Lull=$_POST["Lull"];
$SHA_Incoming  =$_POST["KEY"];


$PassFull = "&avgWind=".$avgWind."&Gust=".$Gust."&Lull=".$Lull."&KEY=".$Pass;
$pass_sha256 = hash('sha256', $PassFull, false);



if (strcmp($pass_sha256, $SHA_Incoming) == 0) {
   
   $query = "INSERT INTO weatherdata.Wind VALUES (NULL, unix_timestamp(now()), CURDATE(), CURTIME(), $avgWind, $Gust, $Lull )"; 
   	   	mysqli_query($con, $query);
	        

}

?>



