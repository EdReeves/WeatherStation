<?php

//Textdata_update.php - Ed Reeves
//Updates JSON file containing text data. 

include 'other/Database_Connection.php';

$result = mysqli_query($con, "SELECT * FROM `tempdat2`  ORDER BY ID DESC LIMIT 1");

while($row =mysqli_fetch_array($result)) {
	$arr['ID']= $row[0];
	$arr['UNIXTIME']= $row[1];
	$arr['ttime']=$row[3];
	$arr['WindSpeed']= $row[4];
	$arr['WindDirection']= $row[5];
	$arr['temperature']=$row[6];
	$arr['pressure']= $row[7];
	$arr['humidity']= $row[8];
	
}

$rain_result = mysqli_query($con, "SELECT SUM(Rainfall) FROM `tempdat2` WHERE `tdate`= CURDATE()");

while($row =mysqli_fetch_array($rain_result)) {

$arr['rainfall']=round($row[0],2);
}

$uptime =  mysqli_query($con, "SELECT DATEDIFF(CURDATE(),'2014-10-25') AS DiffDate");

while($row =mysqli_fetch_array($uptime)) {
	$arr['DiffDate']=$row[0];
}

$DAY_AVERAGE = mysqli_query($con, "SELECT ROUND(AVG(temperature),2)  FROM `tempdat2` WHERE DATE(tdate) = DATE(NOW())");

while($row =mysqli_fetch_array($DAY_AVERAGE)) {
	$arr['Day_AVG'] = $row[0];
}

$DAY_MAX = mysqli_query($con, "SELECT temperature,ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature DESC LIMIT 1");

while($row = mysqli_fetch_array($DAY_MAX)) {
	$arr['Day_MAX'] = $row[0];
	$arr['Day_MAXtime'] = $row[1];
}

$DAY_MIN = mysqli_query($con, "SELECT temperature,ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature ASC LIMIT 1");

while($row = mysqli_fetch_array($DAY_MIN)) {
	$arr['Day_MIN'] = $row[0];
	$arr['Day_MINtime'] = $row[1];
}



$JSONstring =  json_encode($arr);
//echo $JSONstring;

//echo is_array($fred) ? 'Array' : 'not an Array';
$filepath = file('JSON/Textdata.json');
$fp = fopen('JSON/Textdata.json', 'w');
fwrite($fp,$JSONstring);
fclose($fp);
?>