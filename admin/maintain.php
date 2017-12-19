<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="../styler.css">
<link rel="stylesheet" href="../mobile_styler.css">
<link rel="stylesheet" href="../small_styler.css">

<title>Maintenance Page Controller</title>
</head>
<?php
	//error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']!='bbqadmin')
{
	echo "Access Denied.";
	//header("Location: index.php");
}
else
{
?>

<?php

	include("connect.php");
	
	$query="SELECT * FROM `maintenance` LIMIT 1";
	$sqli=$mysqli->query($query);
	
	$row=mysqli_fetch_assoc($sqli);
	
	$tim=$row['duration'];
	
	$fleg=$row['flag'];
	
	$start=$row['start'];

?>


<body>
	<a href="adpanel.php" class="adm">Go Back</a>
	<br><br>
	<div>
	<form method="post" name="mten">
	<input type="submit" value="Set maintenance flag"></input><br><br>
	<input type="number" name="time"></input><br>
	<textarea rows="8" cols="50" name="message" ></textarea><br>
	<input type="radio" name="flag" value="off" required>Off</input><br>
	<input type="radio" name="flag" value="minutes">On for minutes</input><br>
	<input type="radio" name="flag" value="hours">On for hours</input><br>
	
	</form>

	<h1>DO NOT Refresh this page OR ELSE the timer will reset</h1>
<script>
var myVar=setInterval(function () {myTimer()}, 1000);

		function myTimer() 
		{
			var d = new Date();
			var dd = new Date('<?php echo $start;?>');
			var t=d.getTime();
			var tt=dd.getTime();
			var diff = t-tt;
			var k_min = 1000*60;
			var mins=Math.round(diff/k_min);
			var secs=((diff%k_min)/1000).toFixed(0);
			//document.getElementById("timer").innerHTML = d + "...................." + dd;
			
			if(secs<10)
			{
				document.getElementById("times").innerHTML = mins +":0"+secs;
			}
			else
			{
				document.getElementById("times").innerHTML = mins +":"+secs;
			}
			
			document.getElementById("strt").innerHTML = dd;
		}
</script>
	<h3 class="strt"> </h3>
	<h3 class="times"> </h3>
	</div>
</body>
</html>

<?php
	if($_POST)
	{
		if(isset($_POST['flag']))
		{
			$query = "TRUNCATE TABLE `maintenance`";
			$mysqli->query($query);
			
			date_default_timezone_set('America/Chicago');
			$start=date('D M d Y H:i:s \G\M\TO');
			
			$flag=$_POST['flag'];
			$time=$_POST['time'];
			$mess=$_POST['message'];
			$set = "INSERT INTO `maintenance` (`flag`, `duration`, `start`, `message`) VALUES ('$flag', '$time', '$start', '$mess')";
			$mysqli->query($set);
			
			$set2 = "INSERT INTO `maintenance` (`flag`, `duration`, `start`, `message`) VALUES ('ndisplay', '0', '0', '')";
			$mysqli->query($set2);
		}
	

	}
}
?>