window.pages.rainfall = function () {
	$.getJSON('/json/rainfall.json', function (data) {

		$('#rainfallChart').highcharts('StockChart', {
			chart: {
				alignTicks: false
			},
			rangeSelector: {
				buttons: [
					{
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
				text: 'Rainfall Readings'
			},
			yAxis: {
				title: {
					text: 'Rainfall (mm)'
				},
			},
			series: [
				{
					type: 'column',
					name: 'Rainfall (mm)',
					data: data,
				}
			]
		});

	});
};
