<?php

	include("connect.php");
	
		session_start();
if($_SESSION['user']=='bbqadmin')
{

?>
<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://justrun.x10.mx/BBQ/favicon.png">
<link rel="stylesheet" href="../styler.css">
<link rel="stylesheet" href="../mobile_styler.css">
<link rel="stylesheet" href="../small_styler.css">
</head>
<body>
	<table>
		<table class="mainpage">
		<a href="adpanel.php" class="adm">Go Back</a>
			<tr><td><div class="the">Front Page Message</div></td></tr>
			
</body>
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
		$time=0;
		$mess=$_POST['message'];
		$set = "INSERT INTO `maintenance` (`flag`, `duration`, `start`, `message`) VALUES ('$flag', '$time', '$start', '$mess')";
		$mysqli->query($set);
		}
	

	}
}
else
{
	echo "Access Denied for non-admin";
}
?>
</html>