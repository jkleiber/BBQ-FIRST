<?php
include('connect.php');
	
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
<link rel="icon"
      type="image/png"
      href="http://justrun.x10.mx/BBQ/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
<title>The Blue Alliance API 2.0 Access</title>
</head>

<?php
		error_reporting(E_ALL ^ E_NOTICE);
		include('connect.php');
		$say = false;
		if($_POST)
		{
			if(isset($_POST['tba']))
			{
				$tba = $_POST['tba'];
				$ev = file_get_contents("http://www.thebluealliance.com/api/v2/".$_POST['tba']."?X-TBA-App-Id=justin_kleiber:tba_user_json_access:1");
				$json_d = json_decode($ev);
				$json_string = json_encode($json_d, JSON_UNESCAPED_SLASHES |  JSON_PRETTY_PRINT);
				$say = true;
			}
		}
?>

<body>
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	
	<br>
	<div class="the" style="padding:10px;">
		<form name="tbaform" action="#" method="post">
			Enter TBA JSON Query:<br>
<div>			
			<div class="tba">http://www.thebluealliance.com/api/v2/</div><input type="text" id="tbajson" name="tba" value="<?php if(isset($tba)){echo $tba;}?>"/></div><br>
			<a class="standard" href="http://www.thebluealliance.com/apidocs">For more information about The Blue Alliance API, click here</a><br><br>
			<a class="sub" href="#" onclick="document.tbaform.submit();return false;">Submit</a>
		
		</form>
	</div>
	<br>
	<div class="the" style="padding:10px;">
		<u>Raw JSON Data</u>
		<br><br>
		<pre>
		<?php
			if($say)
			{
				echo $json_string;
				$say = false;
			}
		?>
		</pre>
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