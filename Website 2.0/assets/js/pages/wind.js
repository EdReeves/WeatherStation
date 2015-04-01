window.pages.wind = function () {


	var seriesOptions = [],
		seriesCounter = 0,
		names = ['average', 'gust', 'lull'],
		createChart = function () {
			$('#windChart').highcharts('StockChart', {
				rangeSelector : {
					buttons : [
						{
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
						}, {
							type : 'month',
							count : 3,
							text : '3M'
						}, {
							type : 'month',
							count : 6,
							text : '6M'
						}, {
							type : 'year',
							count : 1,
							text : '1Y'
						}, {
							type : 'all',
							count : 1,
							text : 'All'
						}
					],
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
					plotLines : [
						{
							value : 0,
							color : 'black',
							dashStyle : 'shortdash',
							width : 2
						}
					]
				},
				tooltip: {
					crosshairs: [
						{
							width: 1,
							color: 'gray',
							dashStyle: 'shortdot'
						}, {
							width: 2,
							color: 'gray',
							dashStyle: 'shortdot'
						}
					],
					headerFormat: '<small>{point.key}</small><table><br/>',
					pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br/>',
					valueSuffix: ' m/s',
					valueDecimals: 2
				},
				series: seriesOptions
			});
		};

	$.each(names, function (i, name) {
		$.getJSON('/json/wind-'+name+'.json',    function (data) {
			seriesOptions[i] = {
				name: name,
				data: data
			};
			seriesCounter += 1;
			if (seriesCounter === names.length) {
				createChart();
			}
		});
	});


	$.getJSON('/json/wind-direction.json', function (data) {

		var series = [],
			directions = [],
			speeds = {};
		$.each(data, function(direction, values) {
			directions.push(direction);
			$.each(values, function(speed, value) {
				if (typeof speeds[speed] === 'undefined') {
					speeds[speed] = [];
				}
				speeds[speed].push(value);
			});
		});

		$.each(speeds, function(speed, values) {
			series.push({
				stacking: 'normal',
				pointPlacement: 'on',
				name: speed+' m/s',
				data: values
			});
		});

		$('#windRose').highcharts({
			chart: {
				polar: true,
				type: 'column'
			},
			title: {
				text: 'Wind rose for wind data'
			},
			legend: {
				align: 'right',
				verticalAlign: 'top',
				y: 100,
				layout: 'vertical'
			},
			xAxis: {
				tickmarkPlacement: 'on',
				categories: directions
			},
			yAxis: {
				min: 0,
				endOnTick: false,
				showLastLabel: true,
				reversedStacks: false
			},
			tooltip: {
				valueSuffix: '%'
			},
			series: series
		});

	});


};
