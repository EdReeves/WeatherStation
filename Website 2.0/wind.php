<!DOCTYPE html>
<html lang="en" dir="ltr">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/highcharts-more.js"></script>
<script src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script src="/grid-light.js"></script>
<?php include 'wind/Windrose_Update.php';?>
<?php include 'other/Textdata_Update.php';?>
<?php include 'other/Gather_Data.php';?>
<?php include "wind/windupdate.php";?>
<script src="wind/Wind_Chart.js"></script>
<script src="wind/windrose.js"></script>
<?php
$json = file_get_contents("JSON/windrose_data.json"); 
$data2 = json_decode($json,true);

?>


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
      
      <div id="WindChart" style="height: 450px; min-width: 310px"></div>
      

      <div id="windrose" style="min-width: 420px; max-width: 600px; height: 400px; margin: 0 auto"></div>
      <div style="display:none">
  <!-- Source: http://or.water.usgs.gov/cgi-bin/grapher/graph_windrose.pl -->
  <table id="freq" border="0" cellspacing="0" cellpadding="0">
    <tr nowrap bgcolor="#CCCCFF">
      <th colspan="9" class="hdr">Table of Frequencies (percent)</th>
    </tr>
    <tr nowrap bgcolor="#CCCCFF">
      <th class="freq">Direction</th>
      <th class="freq">0-2 m/s</th>
      <th class="freq">2-4 m/s</th>
      <th class="freq">4-6 m/s</th>
      <th class="freq">6-8 m/s</th>
      <th class="freq">8-10 m/s</th>
      <th class="freq">&gt; 10+ m/s</th>
      <!--<th class="freq">Total</th>-->
    </tr>
    
    <tr nowrap>
      <td class="dir">N</td>
      <td class="data"><?php echo $a = $data2['0_2']['1']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['1']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['1']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['1']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['1']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['1']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>   
    
    <tr nowrap>
      <td class="dir">NE</td>
      <td class="data"><?php echo $a = $data2['0_2']['2']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['2']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['2']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['2']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['2']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['2']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>
    
    <tr nowrap>
      <td class="dir">E</td>
      <td class="data"><?php echo $a = $data2['0_2']['0']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['0']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['0']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['0']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['0']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['0']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    
    <tr nowrap>
      <td class="dir">SE</td>
      <td class="data"><?php echo $a = $data2['0_2']['5']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['5']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['5']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['5']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['5']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['5']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>
    
    <tr nowrap>
      <td class="dir">S</td>
      <td class="data"><?php echo $a = $data2['0_2']['4']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['4']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['4']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['4']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['4']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['4']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    
    <tr nowrap>
      <td class="dir">SW</td>
      <td class="data"><?php echo $a = $data2['0_2']['6']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['6']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['6']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['6']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['6']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['6']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>
    
    <tr nowrap>
      <td class="dir">W</td>
      <td class="data"><?php echo $a = $data2['0_2']['7']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['7']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['7']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['7']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['7']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['7']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>
    
    <tr nowrap>
      <td class="dir">NW</td>
      <td class="data"><?php echo $a = $data2['0_2']['3']; ?></td>
      <td class="data"><?php echo $b = $data2['2_4']['3']; ?></td>
      <td class="data"><?php echo $c = $data2['4_6']['3']; ?></td>
      <td class="data"><?php echo $d = $data2['6_8']['3']; ?></td>
      <td class="data"><?php echo $e = $data2['8_10']['3']; ?></td>
      <td class="data"><?php echo $f = $data2['10+']['3']; ?></td>
      <!--<td class="data"><?php echo $a +$b +$c +$d +$e +$f; ?></td>-->
    </tr>   
    
    
  </table>
</div>
      
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
