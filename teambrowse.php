<?php
include('connect.php');
	
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
<title>BBQ FIRST - Event List</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	

<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
?>
	<style>
	.searcher
	{
	height:35px;
	width:85px;
	background-color:#1f1f1f;
	border-radius:5px;
	outline: 1px solid transparent;
	border: 1px solid transparent;
	color:#FFF;
	cursor: pointer;
	}
	.searcher:hover
	{
	background-color:#5f5f5f;
	}
	</style>
<div><h1>FRC Team Search </h1></div>
<form method="get" action="team_info.php">
	<input type="number" name="year" min="2005" max="2015" placeholder="Year" value="2015" required/><br>
	<input type="number" name="tem" placeholder="Team Number" required/><br>
	<input type="submit" value="Go!" class="searcher"/>
<form>
</h1>
</div>




 </div>
<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>

<?php
}
?>