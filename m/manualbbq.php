<html>
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Manual BBQ</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfirst.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>

	<div id="nav">
			<a href="index.html" id="nav">
			</a> 
			<a href="help.html" id="nav_txt">
				Help	
			</a> 
	</div>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	$bbq = 0;
	
	
$sum = 0;
$num = 0;
$i = 0;
$yrs = 0;
	foreach ($_POST as $key => $value) {
		// Do something with $key and $value
		if($value != "" && $value != null)
		{
		$sql = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` WHERE `team_num` = '$value' LIMIT 1");
		$row = mysqli_fetch_assoc($sql);
		$sum += $row["banners"];
		$num++;
		
		if($row["rookie"] != 0)
		{
			$yrs += $row["years"];
		}
	

	$i++;
		}
	}
	if($num != 0)
	{
		$bbq=$sum/$num;
	}
	
	if($yrs == 0)
	{
		$yrs = 1;
	}
	
	$bbqpdq = $bbq/$yrs;
?>

<table id="stats">
<tr id="val">
	<td id="stat">BBQ</td>
	<td id="val"><?php echo $bbq; ?></td>
</tr>
<tr id="val">
	<td id="stat">BBQ PDQ</td>
	<td id="val"><?php echo $bbqpdq; ?></td>
</tr>
</table>
<br>
<table id="region">
<th id="region">Team #</th>
<th id="region">Team</th>
<th id="region">Blue Banners</th>
<th id="region">First Year</th>
<?php
	foreach ($_POST as $key => $value) {
		// Do something with $key and $value
		if($value != "" && $value != null)
		{
		$sqli = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` WHERE `team_num` = '$value' LIMIT 1");
		$row = mysqli_fetch_assoc($sqli);
		
?>
<tr id="regions">
<td id="region"><?php echo $value?></td>
<td id="region"><?php echo $row["nickname"]?></td>
<td id="region"><?php echo $row["banners"]?></td>
<td id="region"> <?php echo $row["rookie"];?> </td>
</tr>
<?php
		}
	}
?>
</table>

</html>