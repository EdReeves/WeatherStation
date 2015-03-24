<?php
//gatherdata.php - Ed Reeves
//Acquires data from JSON and processes it for the site.



$sunrise = date_sunrise(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);
$sunset = date_sunset(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);



$json = file_get_contents("JSON/Textdata.json"); 
$data = json_decode($json,true);

function dewpoint($temp, $humidity) {
  $DewPointnumer = (243.12*(log($humidity/100)+((17.62*$temp)/(243.12+$temp))));
  $Dewpointdenom = (17.62-(log($humidity/100)+((17.62*$temp)/(243.12+$temp))));
  $DewPoint = $DewPointnumer/$Dewpointdenom;
  return $DewPoint;
  }

function cloudbase($temp , $dewpoint) {
  $spread = $temp - $dewpoint;
  $cloudbaseAGL = $spread * 400;
  $Cloudbase = ($cloudbaseAGL*0.30480) + 180;
  return round($Cloudbase,2); 
}

function windchill($temp, $windspeed) {
  if ($windspeed < 2.3) {
    $Windchill = $temp;
    return $Windchill;
  }
  $windspeedKMH = $windspeed * 3.6;
  $Windchill = 13.12+0.6215*$temp-11.37*pow($windspeedKMH,0.16)+0.3965*$temp*pow($windspeedKMH,0.16);
  return round($Windchill,2);

}

$DewPoint = round(dewpoint($data['temperature'], $data['humidity']),2);
$cloudbase = cloudbase($data['temperature'], $DewPoint);
$windchill = windchill($data['temperature'], $data['WindSpeed']);

?>