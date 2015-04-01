<!DOCTYPE html>
<html lang="en" dir="ltr">

	<head>
		<title>Weather Station</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="/assets/js/vendor/jquery.min.js"></script>
		<script src="/assets/js/vendor/mustache.min.js"></script>
		<script src="/assets/js/vendor/page.js"></script>
		<script src="/assets/js/vendor/highstock.highcharts.min.js"></script>
		<script src="/assets/js/vendor/highcharts.min.js"></script>
		<script src="/assets/js/vendor/more.highcharts.min.js"></script>
		<script src="/assets/js/vendor/data.highcharts.min.js"></script>
		<script src="/assets/js/vendor/exporting.highstock.highcharts.min.js"></script>
		<script src="/assets/js/vendor/grid.highcharts.js"></script>
		<script>
			window.pages = {};
		</script>
		<script src="/assets/js/pages/temperature.js"></script>
		<script src="/assets/js/pages/pressure.js"></script>
		<script src="/assets/js/pages/humidity.js"></script>
		<script src="/assets/js/pages/rainfall.js"></script>
		<script src="/assets/js/pages/wind.js"></script>
		<script src="/assets/js/app.js"></script>
		<link rel="stylesheet" href="/assets/css/design.css" />
	</head>

	<body>

		<header>
			<hgroup>
				<h1><a href="/temperature-pressure">Weather Station</a></h1>
				<h3>Arduino based internet weather station based in Sheffield, England.</h3>
			</hgroup>
			<nav>
				<a href="/temperature-pressure">Temperature &amp; Pressure</a>
				<a href="/humidity-rainfall">Humidity &amp; Rainfall</a>
				<a href="/wind">Wind</a>
			</nav>
		</header>

		<section class="stats">

		</section>

		<section class="content">

		</section>

	</body>

</html>
