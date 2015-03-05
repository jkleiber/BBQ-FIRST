<?php
include('connect.php');
	
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query);

$row=mysqli_fetch_assoc($sqli);

$tim=$row['duration'];

$fleg=$row['flag'];

$start=$row['start'];

$mess=$row['message'];


?>
<html>
	<head profile="http://www.w3.org/2005/10/profile">
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
		<link rel="stylesheet" type="text/css" href="styler.css">
	</head>

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
		}
	</script>
	<body>
	<div id="container">
	<?php
	if($fleg=="off")
	{
	?>
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	<?php
	}
	?>
	<br>
	<?php
	if($fleg!="off")
	{
	?>
		<div><p><?php echo $mess;?></p></div>
		<br>
		<div><h1>The codes are currently being barbecued.</h1></div>
		<?php
	}else{
	?>
		<div><h1>Congratulations! There is no maintenance!</h1></div>
	<?php
	}
	if($fleg!="off")
	{
	?>
		<div><h3><?php echo "These codes usually cook in about ". $tim." ".$fleg. ". Thanks for your patience.";?></h3></div>
		<?php
	}else{
	?>
		<div><h3><?php echo "Nothing to see here, except an exceptional GIF of our logo";?></h3></div>
		
	<?php
	}?>
	
		<h3 id="times"> </h3>
		<div><img src="bbqcode.gif"></img></div>
		
		<br>
		
	</div>
	<h6>Maintenance began at</h6><h6> <?php echo $start;?></h6>
	<!--<h6 id="timer"> </h6>!-->
	
		<footer class="nav" class="site-footer">
		
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
	</body>
</html>