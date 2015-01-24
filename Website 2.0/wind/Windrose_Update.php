<?php

//Windrose_gather_JSON.php - Ed Reeves
//This is without a doubt a terribly inefficient way of going about this.
//Looking for recommendations.

include 'other/Database_Connection.php';

//Find the total number of rows.
$results = mysqli_query($con, "SELECT COUNT(*) from tempdat2");

while($row =mysqli_fetch_array($results)) {
      $totalrows = $row[0];
      
      }


//Iterate through and output the percentages for each speed range in each direction.
for ($i=0; $i <= 8 ; $i += 2) { 
	$arrayname = $i . "_";
	$arrayname2 = $i+2; 
	$arrayname3 = $arrayname . $arrayname2;
	
	$result = mysqli_query($con, "SELECT COUNT(WindSpeed), `WindDirection` FROM `tempdat2` WHERE `WindSpeed`>$i AND `WindSpeed` <=$i+2 GROUP BY `WindDirection` ORDER BY WindDirection ASC ");
    $x = 0;
    while($row = mysqli_fetch_array($result)) {
        
        $winddat[$arrayname3][$x] = round((($row[0]/$totalrows)*100),2);
        $x = $x+1;
        
    }
	
}

//one final query for windspeeds >10m/s
$result = mysqli_query($con, "SELECT COUNT(WindSpeed), `WindDirection` FROM `tempdat2` WHERE `WindSpeed`>10  GROUP BY `WindDirection`ORDER BY WindDirection ASC ");
    $x = 0;
    while($row = mysqli_fetch_array($result)) {
        
        $winddat['10+'][$x] = round((($row[0]/$totalrows)*100),2);
        $x = $x+1;
        
    }


//Output to JSON format.
$JSONwinddat = json_encode($winddat);
//echo $JSONwinddat;

$filepath = file('JSON/windrose_data.json');
$fp = fopen('JSON/windrose_data.json', 'w');
fwrite($fp,$JSONwinddat);
fclose($fp);

?>

