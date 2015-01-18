<!DOCTYPE html>
<?php

//Caching (2 mins)
//$cachefile = 'cache/'.basename($_SERVER['PHP_SELF']);
//$cachetime = 2 * 60; // 2 mins
// Serve from the cache if it is younger than $cachetime
//if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
//include($cachefile);
//echo "<!-- Cached ".date('jS F Y H:i', filemtime($cachefile))." -->";
//exit;
//}
//ob_start(); // start the output buffer

$con=mysqli_connect("localhost","XXX","XXX","XXX");
        //check connection
        if (mysqli_connect_errno()) {
          echo "failed" . mysqli_connect_error();
        } 

        $temp_results_today = mysqli_query($con, "SELECT *  FROM `tempdat2` WHERE `tdate` = CURDATE()");
        $temps_all = mysqli_query($con, "SELECT CONCAT(`tdate`,' ', `ttime`), `temperature` FROM `tempdat2` GROUP BY DATE(`tdate`), HOUR(`ttime`/8) ");
        $pressure_results_today = mysqli_query($con, "SELECT *  FROM `tempdat2` WHERE `tdate` = CURDATE()");
        $humidity_results_today = mysqli_query($con, "SELECT *  FROM `tempdat2` WHERE `tdate` = CURDATE()");
        $pressure_all = mysqli_query($con, "SELECT CONCAT(`tdate`,' ', `ttime`), `Pressure`  FROM `tempdat2` GROUP BY DATE(`tdate`), HOUR(`ttime`/8)");
        $humidity_all = mysqli_query($con, "SELECT CONCAT(`tdate`,' ', `ttime`), `Humidity`  FROM `tempdat2` GROUP BY DATE(`tdate`), HOUR(`ttime`/8)");
        $resultq = mysqli_query($con, "SELECT temperature,Pressure,Humidity,WindSpeed,WindDirection,Rainfall,ttime FROM tempdat2 ORDER BY ID DESC LIMIT 1"); 
        $rainfall = mysqli_query($con, "SELECT `tdate`, SUM(Rainfall) AS rain FROM `tempdat2`GROUP BY `tdate`");
        $rain_today = mysqli_query($con, "SELECT `ttime`, SUM(Rainfall) FROM `tempdat2` WHERE `tdate`= CURDATE() GROUP BY HOUR(ttime)");



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
  return $Cloudbase; 
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
  ?>


<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
		<script src="zepto.min.js"></script>
        <script src="mustache.js"></script>
		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      var width_var = 760
      google.load("visualization", "1.1", {packages:["corechart"]});
      google.load("visualization", "1.1", {packages:["bar"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        
        
        var temps_today = google.visualization.arrayToDataTable([
          ['Time', 'Temperature',],
          <?php
          while($row =mysqli_fetch_array($temp_results_today)) {
            echo "['" . $row['ttime'] . "', " . $row['temperature'] . "],";
          
          }
          ?>
        ]);
        var pressure_today = google.visualization.arrayToDataTable([
          ['Time', 'Pressure',],
          <?php
          while($row =mysqli_fetch_array($pressure_results_today)) {
            echo "['" . $row['ttime'] . "', " . $row['Pressure'] . "],";
          }
          ?>
        ]);
        var humidity_today = google.visualization.arrayToDataTable([
          ['Time', 'Pressure',],
          <?php
          while($row =mysqli_fetch_array($humidity_results_today)) {
            echo "['" . $row['ttime'] . "', " . $row['Humidity'] . "],";
          }
          ?>
        ]);
        var data_all = google.visualization.arrayToDataTable([
          ['Time', 'Temperature',],
          <?php
          while($row =mysqli_fetch_array($temps_all)) {
            echo "['" . $row[0] . "', " . $row['temperature'] . "],";
          }
          ?>
        ]);
        var all_pressure = google.visualization.arrayToDataTable([
          ['Time', 'Temperature',],
          <?php
          while($row =mysqli_fetch_array($pressure_all)) {
            echo "['" . $row[0] . "', " . $row[1] . "],";
          }
          ?>
        ]);
        var all_humidity = google.visualization.arrayToDataTable([
          ['Time', 'Humidity',],
          <?php
          while($row =mysqli_fetch_array($humidity_all)) {
            echo "['" . $row[0] . "', " . $row[1] . "],";
          }
          ?>
        ]);
        
        var rainfall_all = google.visualization.arrayToDataTable([
          ['Date', 'Rainfall (mm)',],
          <?php
          while($row =mysqli_fetch_array($rainfall)) {
            echo "['" . $row['0'] . "', " . $row['1'] . "],";
          
          }
          ?>
        ]);
        var rainfall_hourly = google.visualization.arrayToDataTable([
          ['Hour', 'Rainfall (mm)',],
          <?php
          while($row =mysqli_fetch_array($rain_today)) {
            echo "['" . $row['0'] . "', " . $row['1'] . "],";
          
          }
          ?>
        ]);


        var options_temp_all = {
          curveType: 'function',
          vAxis: {title: "Celcius"},
          hAxis: {title: "Time", showTextEvery: 48},
          
          legend: {position: 'none'},
          'width':width_var,
          'height':400,
          fontSize: 10
          

        };
        var options_temp_today = {
          curveType: 'function',
          vAxis: {title: "Celcius"},
          hAxis: {title: "Time", showTextEvery: 130},
          
          legend: {position: 'none'},
          'width':width_var,
          'height':400,
          fontSize: 12
          

        };

        var options_pressure_today = {

          curveType: 'function',
          vAxis: {title: "Millibars"},
          hAxis: {title: "Time", showTextEvery: 130},
          legend: {position: 'none'},
          'width':width_var,
          'height':400
        };

        var options_pressure_all = {

          curveType: 'function',
          vAxis: {title: "Millibars"},
          hAxis: {title: "Time", showTextEvery: 48 },
          legend: {position: 'none'},
          'width':width_var,
          'height':400,
          fontSize: 11
        };

        var options_humidity_today = {
          curveType: 'none',
          vAxis: {title: "%"},
          hAxis: {title: "Time", showTextEvery: 130},
          legend: {position: 'none'},
          'width':width_var,
          'height':400
        };

        var options_humidity_all = {
          curveType: 'none',
          vAxis: {title: "%", 
          viewWindowMode:'explicit', 
          viewWindow:
           {
            max:100, 
            min:40
            }
              },
          hAxis: {title: "Time", showTextEvery: 100},
          legend: {position: 'none'},
          'width':width_var,
          'height':400,
          fontSize: 11
         
        };

        var options_windspeed = {

          //curveType: 'function',
          vAxis: {title: "m/s", 
          viewWindow: {
            min: 0,
          } },
          hAxis: {title: "Time", },
          legend: {position: 'none'},
          'width':width_var,
          'height':400
        };

        var rain_options = {
          chart: {},
          legend: {position:'none'},
          'width':width_var,  

        };
        

        var chart = new google.charts.Bar(document.getElementById('rainfall_hourly_div'));
        chart.draw(rainfall_hourly,rain_options);  
        var chart = new google.charts.Bar(document.getElementById('rainfall_all_div'));
        chart.draw(rainfall_all,rain_options);
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(temps_today, options_temp_today);
        var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
        chart.draw(data_all, options_temp_all);
        var chart = new google.visualization.LineChart(document.getElementById('pressure_today_div'));
        chart.draw(pressure_today, options_pressure_today);
        var chart = new google.visualization.LineChart(document.getElementById('humidity_today_div'));
        chart.draw(humidity_today, options_humidity_today);
        var chart = new google.visualization.LineChart(document.getElementById('pressure_all_div'));
        chart.draw(all_pressure, options_pressure_all);
        var chart = new google.visualization.LineChart(document.getElementById('humidity_all_div'));
        chart.draw(all_humidity, options_humidity_all);
        var chart = new google.visualization.LineChart(document.getElementById('windspeed_today_div'));
        chart.draw(windspeed_today, options_windspeed);
        var chart = new google.visualization.LineChart(document.getElementById('windspeed_all_div'));
        chart.draw(windspeed_all, options_windspeed);
        var chart = new google.charts.Bar(document.getElementById('rainfall_hourly_div'));
        chart.draw(rainfall_hourly,rain_options);

        
      }


    </script>



	</head>
	<body>
		<div class="content ">

		</div>
		<div class="header">
            Weather Station
        </div>
	</body>	
	 <!--- This is a Mustache.js template.  This is used with JSON data to generate the HTML strings that will comprise the user interface --->
    <script id="card-template" type="text/template">
        <div class="card">
            {{#image}}
                <div class="card-image {{ image }}">
                    {{#banner}} <div class="banner"></div> {{/banner}} 
                    {{#caption}} <h2>{{caption}}</h2> {{/caption}}
                </div>
            {{/image}}
            {{#title}} <h1>{{title}}</h1> {{/title}}
            {{#message}} <p>{{{message}}}</p> {{/message}}
        </div>
    </script>

    <script>
        var content, columns, compiledCardTemplate = undefined;
        var MIN_COL_WIDTH = 760;
        
        //data used to render the HTML templates
        var cards_data = [
            {   title:"Today's Stats", 
                message:"<?php
				//create connection
				 
        $raincount = mysqli_query($con, "SELECT SUM(Rainfall) FROM tempdat2 WHERE tdate = CURDATE()");
				$result = mysqli_query($con, "SELECT temperature,Pressure,Humidity,WindSpeed,WindDirection,Rainfall,ttime FROM tempdat2 ORDER BY ID DESC LIMIT 1");
        $rowrain = mysqli_fetch_array($raincount);
        $uptime =  mysqli_query($con, "SELECT DATEDIFF(CURDATE(),'2014-10-25') AS DiffDate");
        $uptime2 = mysqli_fetch_array($uptime);     
        $sunrise = date_sunrise(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);
        $sunset = date_sunset(time(), SUNFUNCS_RET_STRING, 53.3, -1.52, 90, 0);
               while($row = mysqli_fetch_array($result)) {
               
               echo "<p>","Last Reading at: " , $row['ttime'] , "</p>";
               
               echo  "<p>","Current Temperature: " , $row['temperature'], "&degC", "</p>";
               echo  "<p>","Current Pressure: " , $row['Pressure'], "mB", "</p>";
               echo  "<p>","Current Humidity: " , $row['Humidity'], "%", "</p>";
               echo  "<p>","Current Windspeed: " , $row['WindSpeed'], "m/s", "</p>";
               //echo  "<p>","Current Wind Direction: " , $row['WindDirection'],"</p>";
               echo  "<p>","Today's Rainfall: " , round($rowrain[0],2), "mm","</p>";
               echo "<p>", "Sunrise: ", $sunrise, "</p>";
               echo "<p>", "Sunset: ", $sunset, "</p>";
               echo  "<p>", "------", "</p>";
                }	
        $DAY_AVERAGE = mysqli_query($con, "SELECT ROUND(AVG(temperature),2) FROM `tempdat2` WHERE DATE(tdate) = DATE(NOW())");
               while($row = mysqli_fetch_array($DAY_AVERAGE)) {
               echo "<p>", "Today's Average: ", $row[0], "&degC", "</p>";
                }
        $DAY_MAX = mysqli_query($con, "SELECT temperature,ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature DESC LIMIT 1");
                while($row = mysqli_fetch_array($DAY_MAX)) {
                echo "<p>", "Today's High: ", $row[0], "&degC", " at ", $row[1], "</p>";
                }
        $DAY_MIN = mysqli_query($con, "SELECT temperature,ttime FROM tempdat2 WHERE DATE(tdate) = DATE(NOW()) ORDER BY temperature ASC LIMIT 1");
                while($row = mysqli_fetch_array($DAY_MIN)) {
                echo "<p>", "Today's Low: ", $row[0], "&degC", " at ", $row[1], "</p>";
                }        
				        echo  "<p>","Station Uptime: " , $uptime2[0], " days","</p>";
				mysqli_close($con);
				
				
				?>"
				
		  },
            {
                title:"Derived Quantities",
                message: "<?php ($row = mysqli_fetch_array($resultq));
                $dewpoint = dewpoint($row['temperature'], $row['Humidity']);
                echo "<p>", "Dew point: ", round($dewpoint,2), "&degC", "</p>";
                echo "<p>", "Cloud base: ",round(cloudbase($row['temperature'], $dewpoint),2), " meters", "</p>";
                echo "<p>", "Wind chill: ", windchill($row['temperature'],$row['WindSpeed'] ), "&degC", "</p>"; 
                ?>" 
                  }, 
            {   title: "Temperature Readings",
            	message:'<h3>Today&#39s Readings</h3> <div id="chart_div" style="width: 700px; height: 400px;"></div> <h3>All Readings</h3> <div id="chart_div2" style="width: 700px; height: 400px;"></div>'
                },
            {   
                title:"Pressure Readings",
                message:' <h3>Today&#39s Readings</h3> <div id="pressure_today_div" style="width: 700px; height: 400px;"></div> <h3>All Readings</h3>  <div id="pressure_all_div" style="width: 700px; height: 400px;"></div>',
                },
            {
                title:"Humidity Readings",
                message:'<h3>Today&#39s Readings</h3> <div id="humidity_today_div" style="width: 700px; height: 400px;"></div> <h3>All Readings</h3>  <div id="humidity_all_div" style="width: 700px; height: 400px;"></div> ', 
                  },
            {
                title:"Cumulative Rainfall",
                message: '<h3>Hourly</h3><div id="rainfall_hourly_div" style="width: 900px; height: 400px;"></div><h3>Daily</h3> <div id="rainfall_all_div" style="width: 900px; height: 400px;"></div> ',
                  },       
            
                             
            
            
            
        ];


        //page load initialization
        Zepto(function($){
            content = $(".content");
            compiledCardTemplate = Mustache.compile( $("#card-template").html() );
            layoutColumns();
            $(window).resize(onResize);
        })
            
        //resize event handler
        function onResize() {
            var targetColumns = Math.floor( $(document).width()/MIN_COL_WIDTH );
            if ( columns != targetColumns ) {
                layoutColumns();   
            }
        }
        
        //function to layout the columns
        function layoutColumns() {
            content.detach();
            content.empty();
            
            columns = Math.floor( $(document).width()/MIN_COL_WIDTH );
            
            var columns_dom = [];
            for ( var x = 0; x < columns; x++ ) {
                var col = $('<div class="column">');
                col.css( "width", Math.floor(100/columns)+"%" );
                columns_dom.push( col );   
                content.append(col);
            }
            
            for ( var x = 0; x < cards_data.length; x++ ) {
                var html = compiledCardTemplate( cards_data[x] );
                
                var targetColumn = x % columns_dom.length;
                columns_dom[targetColumn].append( $(html) );    
            }
            $("body").prepend (content);
        }
    </script>
    
</html>
<?php
$fp = fopen($cachefile, 'w'); // open the cache file for writing
fwrite($fp, ob_get_contents()); // save the contents of output buffer to the file
fclose($fp); // close the file
ob_end_flush(); // Send the output to the browser
?>
