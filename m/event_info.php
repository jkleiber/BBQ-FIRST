<html>

<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	$bbq = 0;
	$code = $_GET['key'];
	$yer = $_GET['year'];
	
	// /api/v2/event/2010sc/teams
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $code ."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$n=json_decode($string,true);
	$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $code ."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
	$teamlist=json_decode($teams,true);
	usort($teamlist,function($a,$b) {return strnatcasecmp($a['team_number'],$b['team_number']);});
?>

<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - <?php echo $n['short_name'];?> - <?php echo $n['year'];?></title>
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

	$event = $mysqli->query("SELECT * FROM `bbqfirst_db`.`regionaldata` WHERE `reg_key` = '$code' LIMIT 1");
	$e = mysqli_fetch_assoc($event);
	
	$bbq=$e["bbq"];
	$bbqpdq=$bbq/$e["years"];
	$sauce=$e['sauce'];
	
	
?>
<div>
<h1><?php echo $n['name']; ?></h1>
</div>
<table id="stats">
<tr id="val">
	<td id="stat">BBQ</td>
	<td id="val"><?php echo $bbq; ?></td>
</tr>
<tr id="val">
	<td id="stat">SAUCE</td>
	<td id="val"><?php echo $sauce; ?></td>
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

	foreach($teamlist as $t)
	{
	
	/*	THE OLD WAY
	//$text = file_get_contents('http://www.thebluealliance.com/team/'. $t[team_number] .'/history');
	//$win = substr_count($text, "Winner");
	//$chair = substr_count($text, "Regional Chairman");
	//$div_win = substr_count($text, "Division Champion");
	
	$bb=$win+$chair+$div_win;*/
	
	//THE NEW WAY
	$n = $t['team_number'];
	$sql = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` WHERE `team_num` = '$n' LIMIT 1");
	$row = mysqli_fetch_assoc($sql);
	
	if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	{
?>
	<tr id="regions">
	<td id="region"><?php echo $row['team_num'];?></td>
	<td id="region"><?php echo $row['nickname'];?></td>
	<?php $bbyr = (string)$row[$yer];?>
	<td id="region"><?php echo $bbyr;?></td>
	<td id="region"> <?php echo $row["rookie"];?> </td>
	</tr>
<?php  	
	} else {
?>

	<tr id="regions">
	<td id="region"><?php echo $n;?></td>
	<td id="region"><?php echo "N/A";?></td>
	<td id="region"><?php echo "N/A";?></td>
	<td id="region"> <?php echo "N/A";?> </td>
	</tr>
<?php
			}
	}
?>
</table>


</html>