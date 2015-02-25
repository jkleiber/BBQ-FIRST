<?php
	$event = $_GET["name"];
	
	$ev = str_replace("-", " ", $event);
?>
<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon"
      type="image/png"
      href="http://bbqfirst.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
<title><?php echo $ev;?></title>
</head>
<body>
	<div id="nav">
			<a href="index.html" id="nav">
			</a> 
			<a href="help.html" id="nav_txt">
				Help	
			</a> 
	</div>
<div>
<h1><?php echo $ev;?></h1>
</div>

<div>
<?php    
$sum = 0;
$num = 0;
$bbq = 0;
$yrs = 0;
	
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	
	$sql = $mysqli->query("SELECT * FROM `bbqfirst_db`.`CustomTeams`");
		
	while($row = $mysqli->fetch_array($sql))
	{
		
		if($row["$event"] != "" && $row["$event"] != null && $row["$event"] != "0")
        {
        /* OLD WAY
		$text = file_get_contents('http://www.thebluealliance.com/team/'. $row["$event"] .'/history');
         
        $win = substr_count($text, "Winner");
        $chair = substr_count($text, "Regional Chairman");  
        $div_win = substr_count($text, "Division Champion");
     
        $bb=$win+$chair+$div_win; */
       //NEW WAY
		$cold = $row["$event"];
		$tem = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` WHERE `team_num` = '$cold' LIMIT 1");
		$row = $mysqli->fetch_assoc($tem);
		$sum += $row["banners"];
		$num++;
		
		if($row["rookie"] != 0)
		{
			$yrs += $row["years"];
		}
		
		}
    }
	
	if (!$sql) 
	{ 
		die('Error: ' . $mysqli->error($handle)); 
	}
	
    $bbq=$sum/$num;
	
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

<table id="region">
<th id="region">Team #</th>
<th id="region">Team</th>
<th id="region">Blue Banners</th>
<th id="region">First Year</th>

<?php
	$sqli = $mysqli->query("SELECT * FROM `bbqfirst_db`.`CustomTeams`");
		
	while($row = $mysqli->fetch_array($sqli))
	{
		
		if($row["$event"] != "" && $row["$event"] != null && $row["$event"] != "0")
        {
        /* OLD WAY
		$text = file_get_contents('http://www.thebluealliance.com/team/'. $row["$event"] .'/history');
         
        $win = substr_count($text, "Winner");
        $chair = substr_count($text, "Regional Chairman");  
        $div_win = substr_count($text, "Division Champion");
     
        $bb=$win+$chair+$div_win; */
       //NEW WAY
		$cold = $row["$event"];
		$tem = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` WHERE `team_num` = '$cold' LIMIT 1");
		$row = $mysqli->fetch_assoc($tem);
		$sum += $row["banners"];
		$num++;
		
		if($row["rookie"] != 0)
		{
			$yrs += $row["years"];
		}
		
	?>

	<tr id="region">
		<td id="region"><?php echo $cold;?></td>
		<td id="region"><?php echo $row["nickname"];?></td> 
		<td id="region"><?php echo $row["banners"];?></td>
		<td id="region"> <?php echo $row["rookie"];?> </td>
	</tr>

<?php  
		}
    }
	
	if (!$sql) 
	{ 
		die('Error: ' . $mysqli->error($handle)); 
	}
	
?>
</div>
<?php		

?>
</body>
</html>