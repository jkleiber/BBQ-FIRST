<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon"
      type="image/png"
      href="http://justrun.x10.mx/BBQ/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
<title>Custom Event List</title>
</head>

	<div id="nav">
			<a href="index.html" id="nav">
			</a> 
			<a href="help.html" id="nav_txt">
				Help	
			</a> 
	</div>
<br>
<a href="eventupload.html" style="margin-left: 8px;" id="fade">Upload your own team list</a>
<table id="mainpage">
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");

	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	// get custom events list

	$sql = $mysqli->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'bbqfirst_db' AND TABLE_NAME = 'CustomTeams'");

	while($row = $sql->fetch_array(MYSQLI_BOTH))
	{
	
		if($row['COLUMN_NAME'] != "id")
		{
		
		$ev = str_replace("-", " ", $row['COLUMN_NAME']);
?>
<tr>
<td><div><a href="<?='customevent.php?name=' . $row['COLUMN_NAME'] ?>" id="fade"><?php echo $ev;?> </a></div></td>
</tr>
<?php
		}
	}
?>
</table>

</html>