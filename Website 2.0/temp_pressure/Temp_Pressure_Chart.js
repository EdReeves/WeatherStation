$(function () {
    $.getJSON('JSON/Temperature_data.json', function (data) {

        // Create the chart
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
                selected : 1
                
            },
            title : {
                text : 'Temperature Readings'
            },

            tooltip: {
                style: {
                    width: '200px'
                },
                valueDecimals: 4
            },

            yAxis : {
                title : {
                    text : 'Temperature (C)'
                },
                plotLines : [{
                    value : 0,
                    color : 'black',
                    dashStyle : 'shortdash',
                    width : 2
                    
                }]
            },

            series : [{
                name : 'Temperature (celcius)',
                data : data,
                id : 'dataseries'

            
            }, ]
        });
    });
});


$(function () {
    $.getJSON('JSON/Pressure_Data.json', function (data) {

        // Create the chart
        $('#pressure').highcharts('StockChart', {


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
                selected : 1
                
            },
            title : {
                text : 'Pressure Readings'
            },

            tooltip: {
                style: {
                    width: '200px'
                },
                valueDecimals: 4
            },

            yAxis : {
                title : {
                    text : 'Pressuree (mB)'
                },
                plotLines : [{
                    value : 0,
                    color : 'black',
                    dashStyle : 'shortdash',
                    width : 2
                    
                }]
            },

            series : [{
                name : 'Pressuree (mB)',
                data : data,
                id : 'dataseries'

            
            }, ]
        });
    });
});