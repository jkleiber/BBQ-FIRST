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
	
	include("connect.php");
	
	if(isset($_GET['export']))
	{
		$tab=$_GET['export'];
		$backupfile= $tab . '.sql';
		
	}
?>
	<table>
		<table id="mainpage">
		<a href="admin_db_access.php" id="adm">Go Back</a>
		<tr><td><div id="the">Exportable Tables</div></td></tr>
		<?php
			$query = "SHOW TABLES FROM `bbqfrcx1_db`";
			$tables = $mysqli->query($query);
			while($t = $tables->fetch_array(MYSQLI_ASSOC))
			{
		?>
			
			<tr>
				<td>
					<div>
						<a href="?db_type=<?php echo $type?>&export=<?php echo $t['Tables_in_bbqfrcx1_db']?>" id="reg"><?php echo $t['Tables_in_bbqfrcx1_db']?></a>
					</div>
				</td>
			</tr>
		<?php
			}
		?>
				
		</table>
	</table>
<?php
}
else
{
	echo "Access Denied for non-admin user";
}
?>
</body>
</html>