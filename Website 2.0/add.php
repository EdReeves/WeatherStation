<?php
/*
add.php  - Ed Reeves 2015 
Accepts incoming weather data from Arduino, vefifies the sender is legitimate using SHA256 and adds the data to the database. 

*/
   	include 'other/Database_Connection.php';


   $Pass = "XXXX"; //REMOVED FOR GITHUB

   $winddirection= $_POST["winddirection"] ;
   
   $temperature=$_POST["temperature"];
   $pressure=$_POST["pressure"];
   $humidity=$_POST["humidity"];
   $rainfall=$_POST["rainfall"];
   $SHA_Incoming=$_POST["KEY"];

   
   $PassFull = "&winddirection=".$winddirection. "&temperature=".$temperature."&pressure=".$pressure."&humidity=".$humidity."&rainfall=".$rainfall."&KEY=".$Pass;
   $pass_sha256 = hash('sha256', $PassFull, false);
   
   $winddirectionQuotes = "\"" . $winddirection . "\"";
   //echo $PassFull;
   //echo $pass_sha256;

if (strcmp($pass_sha256, $SHA_Incoming) == 0) {
	$query = "INSERT INTO weatherdata.tempdat2 VALUES (NULL, unix_timestamp(now()), CURDATE(), CURTIME(), NULL, $winddirectionQuotes, $temperature, $pressure , $humidity, $rainfall)"; 
   	//echo $query;
   	mysqli_query($con, $query);
	  // mysqli_close($con);

}

?>



