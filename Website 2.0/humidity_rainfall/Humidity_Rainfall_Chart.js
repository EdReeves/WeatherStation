$(function () {
    $.getJSON('JSON/Humidity_Data.json', function (data) {

        // Create the chart
        $('#humditychart').highcharts('StockChart', {


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
                text : 'Humidity Readings'
            },

            tooltip: {
                style: {
                    width: '200px'
                },
                valueDecimals: 4
            },

            yAxis : {
                title : {
                    text : 'Humidity (%)'
                },
                
            },

            series : [{
                name : 'Humdity (%)',
                data : data,
                id : 'dataseries'

            
            }, ]
        });
    });
});

$(function () {
    $.getJSON('JSON/Rainfall_data.json', function (data) {

        // create the chart
        $('#rainfallchart').highcharts('StockChart', {
            chart: {
                alignTicks: false
            },

            rangeSelector: {
                buttons : [{
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
                selected: 1
            },

            title: {
                text: 'Rainfall Readins'
            },

             yAxis : {
                title : {
                    text : 'Rainfall (mm)'
                },
                
            },

            series: [{
                type: 'column',
                name: 'Rainfall (mm)',
                data: data,
                
            }]
        });
    });
});