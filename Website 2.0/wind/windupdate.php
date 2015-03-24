<?php 

//Updates the JSON files which provide data to the charts - Ed Reeves

include 'other/Database_Connection.php';
$curtime = time();


//------------------AVGSpeed------------------

// load the JSON data and delete the line from the array 
$lines = file('JSON/AVGwind.json'); 
$last = sizeof($lines) - 1 ; 
unset($lines[$last]); 

// write the new data to the file 
$fp = fopen('JSON/AVGwind.json', 'w'); 
fwrite($fp, implode('', $lines)); 
fclose($fp); 
$fp = fopen('JSON/AVGwind.json', 'r+'); 
fseek($fp, -1, SEEK_END); 
$pos = ftell($fp);
$LastLine = "";
while((($C = fgetc($fp)) != "[") && ($pos > 0)) {
    $LastLine = $C.$LastLine;
    fseek($fp, $pos--);
}


//Extract time from line and insert newer data into json.
$Datetime_unix = substr($LastLine, 0, 10);
fclose($fp); 
$last_TIME = $Datetime_unix;


DataInsertJSON($last_TIME);

//Add the final closing line back in.
$fp = fopen('JSON/AVGwind.json', 'a' );
fwrite($fp,"\r\n"."]");
fclose($fp);





//Function to insert new records into the json file from the database.
function DataInsertJSON($last_TIME) {
  $fp = fopen('JSON/AVGwind.json', 'a' );
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-2);
  fwrite($fp, ",");
  fclose($fp); 
  
  global $con;
  
  $temp_results = mysqli_query($con, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, AVGwind FROM `Wind` Where UNIXTIME > $last_TIME");

  $fp = fopen('JSON/AVGwind.json', 'a' );
  while($row =mysqli_fetch_array($temp_results)) {
      //echo "[" . $row[0] . ", " . $row[1] . "]" . "<br/>";
      fwrite($fp,  "\r\n"."[" . $row[0]. "," . $row[1] . "],");
      }
  
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-1);
  fclose($fp); 



     
 }

//------------------GustSpeed------------------

// load the JSON data and delete the line from the array 
$lines = file('JSON/GustSpeed.json'); 
$last = sizeof($lines) - 1 ; 
unset($lines[$last]); 

// write the new data to the file 
$fp = fopen('JSON/GustSpeed.json', 'w'); 
fwrite($fp, implode('', $lines)); 
fclose($fp); 
$fp = fopen('JSON/GustSpeed.json', 'r+'); 
fseek($fp, -1, SEEK_END); 
$pos = ftell($fp);
$LastLine = "";
while((($C = fgetc($fp)) != "[") && ($pos > 0)) {
    $LastLine = $C.$LastLine;
    fseek($fp, $pos--);
}


//Extract time from line and insert newer data into json.
$Datetime_unix = substr($LastLine, 0, 10);
fclose($fp); 
$last_TIME = $Datetime_unix;


DataInsertJSON2($last_TIME);

//Add the final closing line back in.
$fp = fopen('JSON/GustSpeed.json', 'a' );
fwrite($fp,"\r\n"."]");
fclose($fp);



//Function to insert new records into the json file from the database.
function DataInsertJSON2($last_TIME) {
  $fp = fopen('JSON/GustSpeed.json', 'a' );
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-2);
  fwrite($fp, ",");
  fclose($fp); 
  
  global $con;
  
  $temp_results = mysqli_query($con, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, Gust FROM `Wind` Where UNIXTIME > $last_TIME");

  $fp = fopen('JSON/GustSpeed.json', 'a' );
  while($row =mysqli_fetch_array($temp_results)) {
      //echo "[" . $row[0] . ", " . $row[1] . "]" . "<br/>";
      fwrite($fp,  "\r\n"."[" . $row[0]. "," . $row[1] . "],");
      }
  
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-1);
  fclose($fp); 
      }

//------------------LullSpeed------------------

// load the JSON data and delete the line from the array 
$lines = file('JSON/LullSpeed.json'); 
$last = sizeof($lines) - 1 ; 
unset($lines[$last]); 

// write the new data to the file 
$fp = fopen('JSON/LullSpeed.json', 'w'); 
fwrite($fp, implode('', $lines)); 
fclose($fp); 
$fp = fopen('JSON/LullSpeed.json', 'r+'); 
fseek($fp, -1, SEEK_END); 
$pos = ftell($fp);
$LastLine = "";
while((($C = fgetc($fp)) != "[") && ($pos > 0)) {
    $LastLine = $C.$LastLine;
    fseek($fp, $pos--);
}


//Extract time from line and insert newer data into json.
$Datetime_unix = substr($LastLine, 0, 10);
fclose($fp); 
$last_TIME = $Datetime_unix;


DataInsertJSON3($last_TIME);

//Add the final closing line back in.
$fp = fopen('JSON/LullSpeed.json', 'a' );
fwrite($fp,"\r\n"."]");
fclose($fp);




//Function to insert new records into the json file from the database.

function DataInsertJSON3($last_TIME) {
  $fp = fopen('JSON/LullSpeed.json', 'a' );
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-2);
  fwrite($fp, ",");
  fclose($fp); 
  
  global $con;
  
  $temp_results = mysqli_query($con, "SELECT UNIX_TIMESTAMP(CONCAT(`tdate`,' ', `ttime`))*1000, Lull FROM `Wind` Where UNIXTIME > $last_TIME");

  $fp = fopen('JSON/LullSpeed.json', 'a' );
  while($row =mysqli_fetch_array($temp_results)) {
      //echo "[" . $row[0] . ", " . $row[1] . "]" . "<br/>";
      fwrite($fp,  "\r\n"."[" . $row[0]. "," . $row[1] . "],");
      }
  
  $stat = fstat($fp);
  ftruncate($fp, $stat['size']-1);
  fclose($fp); 
      }


?>
