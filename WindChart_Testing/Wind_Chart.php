<script type="text/javascript">
<?php
$string = file_get_contents("JSON/AVGwind.json");
$json_a = json_decode($string, true);

$string = file_get_contents("JSON/GustSpeed.json");
$json_b = json_decode($string, true);

$string = file_get_contents("JSON/LullSpeed.json");
$json_c = json_decode($string, true);
?>
var AVGwind = <?php echo json_encode($json_a) ?>;
var GustSpeed = <?php echo json_encode($json_b) ?>;
var LullSpeed = <?php echo json_encode($json_c) ?>; 
$('#temp').highcharts('StockChart', {

            
            rangeSelector : {
                buttons : [{
                    type : 'hour',
                    count : 1,
                    text : '1h'
                }, {
                    type : 'day',
                    count : 1,
                    text : '1D'
                }, {
                    type : 'week',
                    count : 1,
                    text : '1W'
                }, {
                    type : 'month',
                    count : 1,
                    text : '1M'
                },{
                    type : 'month',
                    count : 3,
                    text : '3M'
                },{
                    type : 'month',
                    count : 6,
                    text : '6M'
                },{
                    type : 'year',
                    count : 1,
                    text : '1Y'
                },{
                    type : 'all',
                    count : 1,
                    text : 'All'
                }],
                selected : 2
                
            },
            title : {
                text : 'Wind Readings'
            },

            tooltip: {
                style: {
                    width: '200px'
                },
                valueDecimals: 4
            },

            yAxis : {
                //type: 'logarithmic',
                id: 'my_y',
                
                minorTickInterval: 0.5,
                tickInterval: 2,
                min: 0,
                max: 25,
                title : {
                    text : 'Windspeed (m/s)'
                },
                plotLines : [{
                    value : 0,
                    color : 'black',
                    dashStyle : 'shortdash',
                    width : 2
                    
                }]
            },

            series : [{
                name : 'Average Windspeed',
                data : AVGwind,
                id : 'dataseries'

            
            }, 
             {
                name : 'Gust Speed',
                data : GustSpeed,
                id : 'dataseries'

            
            },
            {
                name : 'Lull Speed',
                data : LullSpeed,
                id : 'dataseries',
                visible: false

            
            } ]
        });
    $('#GustButton').click(function () {
        
        var chart = $('#temp').highcharts(),
        series = chart.series[1];
        var yAxis = chart.get('my_y');
        
        if (series.visible) {
            series.hide();
            extremes = chart.yAxis[0].getExtremes();
            var Ymax = extremes.dataMax; 
            yAxis.setExtremes(0, Ymax);
            
        } else {
            series.show();
            yAxis.setExtremes(0, 25);
        }
    });
    $('#LullButton').click(function () {
        
        var chart = $('#temp').highcharts(),
        series = chart.series[2];
        var yAxis = chart.get('my_y');
        
        if (series.visible) {
            series.hide();
            extremes = chart.yAxis[0].getExtremes();
            var Ymax = extremes.dataMax; 
            yAxis.setExtremes(0, Ymax);
            
        } else {
            series.show();
            //yAxis.setExtremes(0, 25);
        }
    });

</script>
