<?php
include('connect.php');
	
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query);

$row=mysqli_fetch_assoc($sqli);
$fleg=$row['flag'];
if($fleg=="minutes" || $fleg=="hours")
{
include 'downtime.php';
}
else
{
?>

<html>

<?php
	include('connect.php');

	$bbq = 0;
	$code = $_GET['key'];
	$yer = substr($code,0,4);
	
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
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<script type="text/javascript" src="jquery-1.11.1.min.js"></script> 
<script type="text/javascript" src="jquery.tablesorter.js"></script> 
</head>
<div id="container">
<?php include "navheader.html"; ?>
<script>
function linktoteam(key, yr)
{
	location.href = 'team_info.php?tem=' + key + "&year=" +yr;
}
function subform()
{
	document.getElementById("yrs").submit();
}

$(document).ready(function() 
{ 
    $("#teamstable").tablesorter({cssAsc:"regionSortUp",cssDesc:"regionSortDown",cssHeader:"regionSort"}); 
} 
); 

</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45234838-2', 'auto');
  ga('send', 'pageview');

</script>
<div>
<h1><?php echo $n['name']; ?></h1>
<?php

	//$event = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`regionaldata` WHERE `reg_key` = '$code' LIMIT 1");
	$qq = "SELECT regional_info.reg_name, regional_info.year, `regional_info`.`yearweek`, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE regional_info.reg_key = '$code'
				LIMIT 1";
	$event = $mysqli->query($qq) or trigger_error($mysqli->error."[$qq]");
	$num = mysqli_num_rows($event);
	$e = mysqli_fetch_assoc($event);
	
	$year = $e['year'];
	$yweek = $e['yearweek'];
	//echo $yweek;
	$o = $e['sponsored'];
	
	if($yweek!="cmp" && $yweek!=null && $yweek!="" && $yweek!="0")
	{
		$yw = "wk" . ($yweek-1);
		$dispwk = $yweek;
	}
	else if($yweek=="cmp")
	{
		if($o=="official")
		{
			$yw = "wk7";
			$dispwk = "CMP";
		}
		else
		{
			$yw = "cmp";
			$dispwk = "offseason";
		}
	}
	else
	{
		$dispwk = "0";
		$yw = "wk0";
	}
	
	if($num>0)
	{
		$bbq=$e["bbq"];
		$bbqpdq=$e["bbq_pdq"];
		$sauce=$e['sauce'];
		$briquette=$e['briquette'];
		$ribs=$e['ribs'];
		$temcom=$e['teams'];
		
	}
	else
	{
		foreach($teamlist as $t)
		{
			$n = $t['team_number'];
			$quer = "SELECT team_info.nickname, team_info.years, team_info.rookie, `".$yer."`.`".$yw."`, team_info.team_num
			FROM `".$yer."`
			INNER JOIN team_info
			ON `".$yer."`.team_num=team_info.team_num
			WHERE team_info.team_num='$n'";
			$sql = $mysqli->query($quer);
			$rowz = mysqli_fetch_assoc($sql);
			
			$bbqpdq="N/A";
			$sauce="N/A";
			$briquette="N/A";
			$ribs="N/A";
			$bans += $rowz[$yw];
			$yrs += $rowz["years"];
			$bbq = $bans/$yrs;
			$temcom++;
		}
	}
	
	
	
?>

<h3>Week <?php echo $dispwk; ?> - <?php echo $yer; ?></h3>
</div>
<table class="stats">

<tr class="val">
	<td class="stat">BBQ</td>
	<td class="val"><?php echo $bbq; ?></td>
</tr>
<tr class="val">
	<td class="stat">SAUCE</td>
	<td class="val"><?php echo $sauce; ?></td>
</tr>
<tr class="val">
	<td class="stat">BBQ PDQ</td>
	<td class="val"><?php echo $bbqpdq; ?></td>
</tr>
<tr class="val">
	<td class="stat">BRIQUETTE</td>
	<td class="val"><?php echo $briquette; ?></td>
</tr>
<tr class="val">
	<td class="stat">RIBS</td>
	<td class="val"><?php echo $ribs; ?></td>
</tr>
<tr class="val">
	<td class="stat">Teams Competing</td>
	<td class="val"><?php echo $temcom; ?></td>
</tr>
</table>

<br>

<table id="teamstable" class="region">
<thead>
<th class="region">Team #</th>
<th class="region">Team</th>
<th class="region">Blue Banners</th>
<th class="region">First Year</th>
</thead>
<tbody>
<?php	
$flag = $year . "cmp";
if($code == '2014cmp')
{
	foreach($n["alliances"] as $all) {
		foreach ($all['picks'] as $key => $value) {
	
	/*	THE OLD WAY
	//$text = file_get_contents('http://www.thebluealliance.com/team/'. $t[team_number] .'/history');
	//$win = substr_count($text, "Winner");
	//$chair = substr_count($text, "Regional Chairman");
	//$div_win = substr_count($text, "Division Champion");
	
	$bb=$win+$chair+$div_win;*/
	
	//THE NEW WAY
	$value = str_replace("frc","", $value);
	$n = $value;
	$quer = "SELECT team_info.nickname, team_info.years, team_info.rookie, `".$year."`.`".$yw."`, team_info.team_num
	FROM `".$year."`
	INNER JOIN team_info
	ON `".$year."`.team_num=team_info.team_num
	WHERE team_info.team_num='$n'";
	$sql = $mysqli->query($quer);
	//$sql = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$n' LIMIT 1");
	$row = mysqli_fetch_assoc($sql);
	
	//if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	//{
	
	$vars = array($n, $yer);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace(" ","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoteam(<?php echo $jsvars;?>)'>
	<td class="region"><?php echo $n;?></td>
	<td class="region"><?php if($row['nickname']!=""){echo $row['nickname'];}else{echo "N/A";}?></td>
	<td class="region"><?php if($row[$yw]!="" || $row[$yw]!=null){echo $row[$yw];}else{echo "0";};?></td>
	<td class="region"> <?php if($row['rookie']!=""){echo $row["rookie"];}else{echo "N/A";}?> </td>
	</tr>
<?php  	
	//} else {
?>
<!--
	<tr class="tregions">
	<td class="region"><?php echo $n;?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"> <?php echo "N/A";?> </td>
	</tr>
!-->
<?php
		//	}
	}
	}
}
else
{
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
	$quer = "SELECT team_info.nickname, team_info.years, team_info.rookie, `".$yer."`.`".$yw."`, team_info.team_num
	FROM `".$yer."`
	INNER JOIN team_info
	ON `".$yer."`.team_num=team_info.team_num
	WHERE team_info.team_num='$n'";
	$sql = $mysqli->query($quer);
	//$sql = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$n' LIMIT 1");
	$row = mysqli_fetch_assoc($sql);
	
	//if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	//{
	
	$vars = array($n, $yer);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace(" ","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoteam(<?php echo $jsvars;?>)'>
	<td class="region"><?php echo $n;?></td>
	<td class="region"><?php if($row['nickname']!=""){echo $row['nickname'];}else{echo "N/A";}?></td>
	<td class="region"><?php if($row[$yw]!="" || $row[$yw]!=null){echo $row[$yw];}else{echo "0";};?></td>
	<td class="region"> <?php if($row['rookie']!=""){echo $row["rookie"];}else{echo "N/A";}?> </td>
	</tr>
<?php  	
	//} else {
?>
<!--
	<tr class="tregions">
	<td class="region"><?php echo $n;?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"> <?php echo "N/A";?> </td>
	</tr>
	!-->
<?php
		//	}
	}
}
?>
</tbody>
</table>
 </div>
<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>

<?php
}
?>