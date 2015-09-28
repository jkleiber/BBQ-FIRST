<?php

include('connect.php');
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query);

$row=mysqli_fetch_assoc($sqli);
$fleg=$row['flag'];
if($fleg=="on_minutes" || $fleg=="on_hours")
{
include 'downtime.php';
}
else
{
?>

<html>
	
	
	<head profile="http://www.w3.org/2005/10/profile">
		<title>BBQ FIRST - Season Comparisons</title>
		<link rel="icon" type="image/png" href="http://bbqfrc.x10host.com/favicon.png">
		<link rel="stylesheet" href="styler.css">
		<script src="Chart.js"></script>
		<script src="jquery-1.11.1.min.js"></script>
	</head>
	
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-45234838-2', 'auto');
		ga('send', 'pageview');
	</script>

	<script>
	var stats = [];
	
	function getYear(url)
	{
		var yearData = [];
		return $.getJSON(url).then(function(data){
			$.each(data, function (index, val) {
				console.log(val);
				yearData.push(val);
			});
			return yearData;
		});
	}
	
	$(document).ready(function() 
	{ 
		var context = document.getElementById("bbqChart").getContext("2d");
		var cxt = document.getElementById("briquetteChart").getContext("2d");
		var ctxt = document.getElementById("ribChart").getContext("2d");
		/*
		var data = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"],
			datasets : [
				{
					label: "Highest BBQ Event",
				    fillColor: "rgba(255,122,0,0.2)",
				    strokeColor: "rgba(255,122,0,1)",
				    pointColor: "rgba(255,122,0,1)",
				    pointStrokeColor: "#FFA049",
				    pointHighlightFill: "#FFA049",
				    pointHighlightStroke: "rgba(255,122,0,1)",
				    data: [0,0,0,0,0,0,0,0,0,0,0]
				}
			
			]
		};
		*/
		var data = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015","2016"],
			datasets : [
				{
					label: "Highest BBQ Event",
				    fillColor: "rgba(255,122,0,0.4)",
				    strokeColor: "rgba(255,122,0,1)",
				    highlightFill: "#FFA049",
				    highlightStroke: "rgba(255,122,0,1)",
				    data: [0,0,0,0,0,0,0,0,0,0,0,0]
				}
			
			]
		};
		var bdata = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015","2016"],
			datasets : [
				{
					label: "Highest BRIQUETTE Event",
				    fillColor: "rgba(71,71,71,0.2)",
					strokeColor: "rgba(71,71,71,1)",
				    highlightFill: "#696969",
				    highlightStroke: "rgba(71,71,71,1)",
				    data: [0,0,0,0,0,0,0,0,0,0,0,0]
				}
			
			]
		};
		
		var rdata = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015","2016"],
			datasets : [
				{
					label: "Highest RIB Event",
				    fillColor: "rgba(255,122,0,0.4)",
				    strokeColor: "rgba(255,122,0,1)",
				    highlightFill: "#FFA049",
				    highlightStroke: "rgba(255,122,0,1)",
				    data: [0,0,0,0,0,0,0,0,0,0,0,0]
				}
			
			]
		};
	
		var bbqChart = new Chart(context).Bar(data);
		getYear("getbestyearevents.php").then(function(returned){
			for(var i=0;i<12;i++)
			{
				bbqChart.datasets[0].bars[i].value = returned[i];
				bbqChart.update();
			}
		});
		
		var briquetteChart = new Chart(cxt).Bar(bdata);
		getYear("getbestbriquetteevents.php").then(function(returned){
			for(var i=0;i<12;i++)
			{
				briquetteChart.datasets[0].bars[i].value = returned[i];
				briquetteChart.update();
			}
		});
		
		var ribsChart = new Chart(ctxt).Bar(rdata);
		getYear("getbestribevents.php").then(function(returned){
			for(var i=0;i<12;i++)
			{
				ribsChart.datasets[0].bars[i].value = returned[i];
				ribsChart.update();
			}
		});
		
		
		var Chartjs = Chart.noConflict();
	});
	</script>
	
	<body>
	
		<div id="container">
			<?php include "navheader.html"; ?>
			<h1>Highest Event BBQ By Year</h1>
			<canvas id="bbqChart" width="800" height="400"></canvas>
			<br>
			<h1>Highest Event BRIQUETTE By Year</h1>
			<canvas id="briquetteChart" width="800" height="400"></canvas>
			<br>
			<h1>Highest Event RIB By Year</h1>
			<canvas id="ribChart" width="800" height="400"></canvas>
		
		</div>
		</div>
		<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a> - <a href="recognition.php" class="fstd">Acknowledgements</a>
		</footer>
	</body>
</html>

<?php

}

/*
,
{
	label: "Average BBQ",
    fillColor: "rgba(71,71,71,0.2)",
    strokeColor: "rgba(71,71,71,1)",
    pointColor: "rgba(71,71,71,1)",
    pointStrokeColor: "#696969",
    pointHighlightFill: "#696969",
    pointHighlightStroke: "rgba(71,71,71,1)",
    data: []
}
*/

?>

