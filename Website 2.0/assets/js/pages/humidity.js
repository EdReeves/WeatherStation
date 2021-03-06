window.pages.humidity = function () {
	$.getJSON('/json/humidity.json', function (data) {

		$('#humdityChart').highcharts('StockChart', {
		rangeSelector: {
			buttons: [
				{
					type: 'hour',
					count: 1,
					text: '1h'
				}, {
					type: 'day',
					count: 1,
					text: '1D'
				}, {
					type: 'week',
					count: 1,
					text: '1W'
				}, {
					type: 'month',
					count: 1,
					text: '1M'
				}, {
					type: 'month',
					count: 3,
					text: '3M'
				}, {
					type: 'month',
					count: 6,
					text: '6M'
				}, {
					type: 'year',
					count: 1,
					text: '1Y'
				}, {
					type: 'all',
					count: 1,
					text: 'All'
				}
			],
			selected: 1
		},
		title: {
			text: 'Humidity Readings'
		},
		yAxis: {
			title: {
				text: 'Humidity (%)'
			},
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
			valueSuffix: ' %',
			valueDecimals: 0
		},
		series: [
			{
				name: 'Humdity (%)',
				data: data,
				id: 'dataseries'
			}
		]
		});

	});
};
