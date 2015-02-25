<?php
$mysqli = new mysqli('localhost','bbqfrcx1_bbquser','bbqpass', "bbqfrcx1_db");
	
if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query);

$row=mysqli_fetch_assoc($sqli);
$fleg=$row['flag'];
if($fleg=="minutes" || $fleg=="hours")
{
include 'downtime.php';
}
else
{
?>

<html>
	
	
	<head profile="http://www.w3.org/2005/10/profile">
	<title>BBQ FIRST</title>
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
	
	<body onload="wid()">
	<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	<br>
	<br>
	<script>
	function wid()
	{
		var screenWidth = window.screen.width;
		document.getElementById("reg").setAttribute("href", "regionals.php?sw=" + screenWidth +"&year=2014");
	}
	/*
	function fadehover(id)
	{
		var a = document.getElementById(id);
		step(1,function(){
							a.setAttribute("color", "#FFF");
						});
	} */
	function step(seconds, action)
	{
		var counter = 0;
		var time = window.setInterval( function ()
		{
			counter++;
			if ( counter >= seconds )
			{
				action();
				window.clearInterval( time );
			}
		}, 1000 );
	}
	</script>
	<!--
		<table class="mainpage">
			<tr><td><div><a href="regionfacts.php" class="fade">Event BBQ Rankings</a></div></td></tr>
			<tr><td><div><a onclick="wid()" href="regionals.php?sw=1200" class="reg">BBQ for all FRC Events</a></div></td></tr>
			<tr><td><div><a href="manual_bbq.php" class="fade">Manual BBQ Calculator</a></div></td></tr>
			<!--<tr><td><div><a href="eventbrowser.php" class="fade">View and Upload Custom Events</a></div></td></tr> 
		</table> 
		<div>
		<table class="ind">
			
			<tr class="ind"><td id="indsm"><div><a href="regionfacts.php" class="fade">Current Season Event BBQ Rankings</a></div></td></tr>
			<tr class="ind"><td><div><a onclick="wid()" href="regionals.php?sw=1200" class="reg">BBQ for all FRC Events</a></div></td></tr>
			<tr class="ind"><td><div><a href="manual_bbq.php" class="fade">Manual BBQ Calculator</a></div></td></tr>
			<tr class="ind"><td><div><a href="allregionfacts.php" class="fade">All-time Event BBQ Rankings</a></div></td></tr>
		</table>
		!-->
		<table>
				<table class="mainpage">
					<tr><td><div class="the">Main</div></td></tr>
					<tr><td>
						<div>
							<a onclick="wid()" href="regionals.php?sw=1200&year=2015" class="reg">FRC Event Browser by Year</a>
						</div>
					</td></tr>
					<tr><td>
						<div>
							<a href="teambrowse.php" class="fade">FRC Team Search Utility</a>
						</div>
					</td></tr>
					
				</table>
				
				<br>
				<table class="mainpage">
					<tr><td><div class="the">Rankings</div></td></tr>
				<!--	<tr><td>
						<div>
							<a href="allregionfacts.php" class="fade">All-time Event BBQ Rankings</a>
						</div>
					</td></tr>
					<tr><td>
						<div>
							<a href="allsaucefacts.php" class="fade">All-time Event SAUCE Event Rankings</a>
						</div>
					</td></tr> -->
					<tr><td>
						<div>
							<a href="regionfacts.php" class="fade">BBQ Event Rankings</a>
						</div>
					</td></tr>
					<tr><td>
						<div>
							<a href="saucefacts.php" class="fade">SAUCE Event Rankings</a>
						</div>
					</td></tr>
					<tr><td>
						<div>
							<a href="briquettefacts.php" class="fade">BRIQUETTE Event Rankings</a>
						</div>
					</td></tr>
					<tr><td>
						<div>
							<a href="ribsfacts.php" class="fade">RIBS Event Rankings</a>
						</div>
					</td></tr>
				</table>
				
				<br>
				<table class="mainpage">
					<tr><td><div class="the">Labs</div></td></tr>
					<tr><td>
						<div>
							<a href="manual_bbq.php" class="fade" >Manual Calculator</a>
						</div>
					</td></tr>
			<!--		<tr><td>
						<div>
							<a href="" class="fade">View and Upload Custom Events</a>
						</div>
					</td></tr> !-->
				</table>
			</tbody>
		</table>
		</div>
	</div>
		<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a> - <a href="recognition.php" class="fstd">Acknowledgements</a>
		</footer>
	</body>
</html>
<?php
}
?>