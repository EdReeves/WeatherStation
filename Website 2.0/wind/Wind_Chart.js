


$(function () {
    var seriesOptions = [],
        seriesCounter = 0,
        names = ['AVGwind', 'GustSpeed', 'LullSpeed'],
        // create the chart when all data is loaded
        createChart = function () {

            $('#WindChart').highcharts('StockChart', {

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
                text : 'Average/Gust/Lull Wind Readings'
            },

                yAxis: {
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

                

                tooltip: {
                    crosshairs: [{
                        width: 1,
                        color: 'gray',
                        dashStyle: 'shortdot'
                    }, {
                        width: 2,
                        color: 'gray',
                        dashStyle: 'shortdot'
            }],
                    headerFormat: '<small>{point.key}</small><table><br/>',
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
                    valueSuffix: ' m/s',
                    valueDecimals: 2
                },

                series: seriesOptions
            });
        };

    $.each(names, function (i, name) {

        $.getJSON('JSON/' + name + '.json',    function (data) {

            seriesOptions[i] = {
                name: name,
                data: data
            };

            // As we're loading the data asynchronously, we don't know what order it will arrive. So
            // we keep a counter and create the chart when all the data is loaded.
            seriesCounter += 1;

            if (seriesCounter === names.length) {
                createChart();
            }
        });
    });
});


    $('#GustButton').click(function () {
        
        var chart = $('#WindChart').highcharts(),
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
        
        var chart = $('#WindChart').highcharts(),
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
