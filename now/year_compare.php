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
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
	<link rel="stylesheet" href="styler.css">
	</head>
	
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-45234838-2', 'auto');
		ga('send', 'pageview');
	</script>

	<script type="text/javascript" src="https://cdnjs.com/libraries/chart.js"></script>
	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	
	<script>
	$(document).ready(function() 
	{ 
		var context = document.getElementById("myChart").getContext("2d");
		var myNewChart = new Chart(context).Bar(data);
		
		var data = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"],
			datasets : [
				{
					label: "Highest BBQ Event",
				    fillColor: "rgba(220,220,220,0.2)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    pointHighlightFill: "#fff",
				    pointHighlightStroke: "rgba(220,220,220,1)",
				    data: [65, 59, 80, 81, 56, 55, 40]
				},
				{
					label: "Average BBQ",
				    fillColor: "rgba(220,220,220,0.2)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    pointHighlightFill: "#fff",
				    pointHighlightStroke: "rgba(220,220,220,1)",
				    data: [65, 59, 80, 81, 56, 55, 40]
				}
			
			]
		}
	});
	</script>
	
	<body>
	
		<div id="container">
		<?php include "navheader.html"; ?>
		
			<canvas id="myChart" width="800" height="600"></canvas>
		
		</div>
	</body>

