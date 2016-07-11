<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="../styler.css">
<link rel="stylesheet" href="../mobile_styler.css">
<link rel="stylesheet" href="../small_styler.css">

<title>Admin Panel Home</title>

</head>
<body>
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{

	include("connect.php");
	
?>
	<table>
		<table class="mainpage">
		<a href="../index.php" class="adm">Go to Main Website</a>
		<br>
			<tr><td><div class="the">Admin Panel</div></td></tr>
			
			<tr>
				<td>
					<div>
						<a href="../auto_update/index.php" class="reg">Manual Update Interface</a>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						<a href="admin_file_access.php" class="reg">Website File Access</a>
					</div>
				</td>
			</tr>
				<tr>
				<td>
					<div>
						<a href="maintain.php" class="reg">Maintenance</a>
					</div>
				</td>
			</tr>	
			</tr>
				<tr>
				<td>
					<div>
						<a href="thephp.php" class="reg">PHP Settings</a>
					</div>
				</td>
			</tr>	
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