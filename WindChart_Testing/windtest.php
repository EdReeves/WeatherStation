<!DOCTYPE html>
<html lang="en" dir="ltr">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="/grid-light.js"></script>


<?php include "wind/windupdate.php";?>




<h1>Just a Test</h1>
<body>
<div id="temp" style="height: 600px; min-width: 310px"></div>
<?php include "wind/Wind_Chart.php"; ?>
<button id="GustButton">Toggle Gust Series</button>
<button id="LullButton">Toggle Lull Series</button>
</body>

