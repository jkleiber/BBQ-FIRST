<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="../styler.css">
</head>
<body>
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{

	include("connect.php");
	
?>

	<title>BBQ MUI</title>
<div id="container">	
	<div class="nav">
			<a href="../index.php" class="nav"></a>
	</div>
	<br><br><br><br>
	
	<br><br>
	<h1>BBQ Manual Update Interface</h1>
	
	<table class="mainpage">
		<tr>
			<td>
				<div>
					<a href="teams/index.php" class="fade">Update Team Data</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<a href="events/index.php" class="fade">Update Event Data</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<a href="new_year/index.php" class="fade">Setup Next Year</a>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<a href="update_complete.php" class="fade">Refresh Last Updated Indicator</a>
				</div>
			</td>
		</tr>
	</table>

<?php
}
else
{
	echo "Access Denied for non-admin.";
}
?>