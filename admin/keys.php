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
		<a href="../index.php" class="adm">Go to Main Website</a>
		<br>
			<tr><td><div class="the">Admin Panel</div></td></tr>
			
			<tr>
				<td>
					<div>
						1, 9999 returns All Teams - Manual BBQ
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						1, 2, 3, 4, 5, 6, 7, 8, 9, 10 returns Top Ten Teams - Manual BBQ
					</div>
				</td>
			</tr>
			<tr> 
				<td>
					<div>
						2789, 2789, 987, 330, 148, 4476, 624, 1241 ,469 returns Secret Web Power - Manual BBQ
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