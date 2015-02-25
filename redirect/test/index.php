<?php
error_reporting(E_ALL ^ E_NOTICE);

$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
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

$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/2015?X-TBA-App-Id=justin_kleiber:event_scraper:1");
$regional=json_decode($string,true);
usort($regional,function($a,$b) {return strnatcasecmp($a['start_date'],$b['start_date']);});
?>
<html>
	
	
	<head profile="http://www.w3.org/2005/10/profile">
	<title>BBQ EventCast</title>
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfirst.x10host.com/favicon.png">
	<link rel="stylesheet" href="../styler.css">
	</head>
	
	<body>
		<div id="container">
			<div class="nav">
				<a href="index.php" class="nav"></a>
			</div>
			
			<table class="mainpage">
				<tr>
					<td>
						<a href="EventCast/">Go To EventCast</a>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
<?php
}
?>