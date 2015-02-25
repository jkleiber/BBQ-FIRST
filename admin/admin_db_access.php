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
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{

	$mysqli = new mysqli('localhost','bbqfrcx1_bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
?>
	<table>
		<table class="mainpage">
		<a href="adpanel.php" class="adm">Go Back</a>
			<tr><td><div class="the">Database Access</div></td></tr>
			<tr>
				<td>
					<div>
						<a href="export_from_db.php" class="reg">Export a Working Table</a>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						<a href="" class="reg">Query the Databases</a>
					</div>
				</td>
			</tr>
					
		</table>
	</table>
<?php
}
else
{
	echo "Access Denied for non-admin";
}
?>
</body>
</html>