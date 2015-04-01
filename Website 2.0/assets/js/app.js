$(function () {
	
	Highcharts.setOptions({
		global: {
			useUTC: false
		}
	});
	
	// Load stats template
	$.getJSON('/json/misc.json', function (data) {
		$.get('/assets/templates/stats.mustache', function (template) {
			$('section.stats').html(Mustache.render($(template).filter('#statsTemplate').html(), data));
		});
	});

	// Redirect homepage
	page('/', function () {
		page.redirect('/temperature-pressure');
	});

	// Temperature and Pressure
	page('/temperature-pressure', function () {

		$.get('/assets/templates/temperature-pressure.mustache', function (template) {
			$('section.content').html(Mustache.render($(template).filter('#temperaturePressureTemplate').html()));
			window.pages.temperature();
			window.pages.pressure();
		});

	});

	// Humidity and Rainfall
	page('/humidity-rainfall', function () {

		$.get('/assets/templates/humidity-rainfall.mustache', function (template) {
			$('section.content').html(Mustache.render($(template).filter('#humidityRainfallTemplate').html()));
			window.pages.humidity();
			window.pages.rainfall();
		});

	});

	// Wind
	page('/wind', function () {

		$.get('/assets/templates/wind.mustache', function (template) {
			$('section.content').html(Mustache.render($(template).filter('#windTemplate').html()));
			window.pages.wind();
		});

	});

	// No matching page found
	page('*', function () {
		page.redirect('/');
	});

	// Start routing
	page();

});
