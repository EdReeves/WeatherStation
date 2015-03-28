<!DOCTYPE html>
<html lang="en" dir="ltr">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="/grid-light.js"></script>
<?php include 'temp_pressure/Temp_Pressure_update.php';?>  
<?php include 'other/Textdata_update.php';?>
<?php include 'other/Gather_Data.php';?>

<script src="temp_pressure/Temp_Pressure_Chart.js"></script>



<script type="text/javascript">
<!-- 
if (screen.width <= 699) {
document.location = "old_site/oldsite.php";
}
function iCheck() //User Agent checker courtesy of Andrew Hedges http://davidwalsh.name/detect-ipad
{
    // For use within normal web clients 
    var isiPad = navigator.userAgent.match(/iPad/i) != null;
    var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
    var isiPod = navigator.userAgent.match(/iPod/i) != null;
    var isnexus = navigator.userAgent.match(/Nexus/i) != null;

    if(isiPad || isiPhone || isiPod || isnexus)
    {
        document.location = "old_site/oldsite.php";
    }
}
//-->
</script>




<head>
<title>Weather Station</title>
<meta charset="iso-8859-1">
<link rel="stylesheet" href="styles/layout.css" type="text/css">
<!--[if lt IE 9]><script src="scripts/html5shiv.js"></script><![endif]-->
</head>
<body>
  <body onload="iCheck();">
<div class="wrapper row1">
  <header id="header" class="clear">
    <div id="hgroup">
      <h1><a href="#">Weather Station</a></h1>
      <h2>Arduino based internet weather station based in Sheffield, England.</h2>
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Temperature &amp; Pressure</a></li>
        <li><a href="humidity-rainfall.php">Humidity &amp; Rainfall</a></li>
        <li><a href="wind.php">WindSpeed &amp; Wind Direction</a></li>
        <li class="last"><a href="old_site/oldsite.php">Previous Website</a></li>
      </ul>
    </nav>
  </header>
</div>
<!-- content -->
<div class="wrapper row2">
  <div id="container" class="clear">
    <section id="slider"></section>
    <!-- content body -->
    <aside id="left_column">
      <h2 class="title">Measured data</h2>
      <nav>
        <ul>
          
          <p>Last Reading at: <?php echo $data['ttime'];?></p>
          <P>Current Temperature: <?php echo $data['temperature'];?>&deg;C</p>
          <P>Current Pressure: <?php echo $data['pressure'];?> mB</p>
          <P>Current Humidity: <?php echo $data['humidity'];?>%</p>
          <P>Current Average Windspeed: <?php echo $data['WindSpeed'];?>m/s</p>
          <p>Current Wind Direction: <?php echo $data['WindDirection'];?></p>
          <P>Today's Rainfall: <?php echo $data['rainfall'];?>mm</p>
          <P>Station Uptime: <?php echo $data['DiffDate'];?> days</p>

          

        </ul>
      </nav>
      <!-- /nav -->
      <h2 class="title">Calculated Data</h2>
      <section class="last">
        <p> Sunrise: <?php echo $sunrise;?></p>
        <p> Sunset: <?php echo $sunset;?> </p>
        <p> Today's Average: <?php echo $data['Day_AVG'];?>&deg;C </p>
        <p> Today's High: <?php echo $data['Day_MAX'];?>&deg;C at <?php echo $data['Day_MAXtime'];?> </p>
        <p> Today's Low: <?php echo $data['Day_MIN'];?>&deg;C at <?php echo $data['Day_MINtime'];?> </p>
        <p> Dew point: <?php echo $DewPoint;?>&deg;C </p>
        <p> Cloud base: <?php echo $cloudbase;?> meters </p>
        <p> Wind chill: <?php echo $windchill;?>&deg;C </p>
      </section>
      <!-- /section -->
    </aside>
    <!-- main content -->
    <div id="content">
      <div id="temp"></div>
      <div id="pressure"></div>
      
    </div>
    <!-- / content body -->
  </div>
</div>
<!-- footer -->

<div class="wrapper row3">
  <footer id="footer" class="clear">
    
    <p class="fl_right">Template by <a href="http://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
  </footer>
</div>
</body>
</html>
