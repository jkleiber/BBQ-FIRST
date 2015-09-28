<?php
error_reporting(E_ALL ^ E_NOTICE);
include('connect.php');

$bbq = 0;	
$sum = 0;
$num = 0;
$i = 0;
$yrs = 0;

		$arr = array();
		//$sl = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` ORDER BY `team_num` DESC LIMIT 1");
		//$ow = mysqli_fetch_assoc($sl);
		//$top= $ow['team_num'];
		
	foreach ($_GET as $key => $value) {
		// Do something with $key and $value
		if($value != "" && $value != null)
		{
		$qq = "SELECT team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				WHERE team_info.`team_num`='$value'";
		$sql = $mysqli->query($qq);
		//$sql = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$value' LIMIT 1");
		$row = mysqli_fetch_assoc($sql);
		$sum += $row["cmp"];
		$num++;
		
		if($row["rookie"] != 0)
		{
			$yrs += $row["years"];
		}
	
		$arr[] = $value;
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
	
	$alltems=false;
	$topten=false;
	$sp=false;
	
	if(count($arr)==2 && $arr[1]==9999 && $arr[0]==1)
	{
		$alltems=true;
		$topten=false;
		$sp=false;
		$bbq = 0;	
		$sum = 0;
		$num = 0;
		$i = 0;
		$yrs = 0;
		$sl = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`special` WHERE `name`='alltems' LIMIT 1");
		$row = mysqli_fetch_assoc($sl);
		$bbq=$row['bbq'];
		$bbqpdq=$row['bbqpdq'];
	}
	else if(count($arr)==10 && $arr[0]==1 && $arr[1]==2 && $arr[2]==3 && $arr[3]==4 && $arr[4]==5 && $arr[5]==6 && $arr[6]==7 && $arr[7]==8 && $arr[8]==9 && $arr[9]==10)
	{
		$alltems=false;
		$topten=true;
		$sp=false;
		$bbq = 0;	
		$sum = 0;
		$num = 0;
		$i = 0;
		$yrs = 0;
		$sl = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`special` WHERE `name`='toptems' LIMIT 1");
		$row = mysqli_fetch_assoc($sl);
		$bbq=$row['bbq'];
		$bbqpdq=$row['bbqpdq'];
	}
	else
	{
		$topten=false;
		$alltems=false;
		$sp=false;
	}
?>

<html>

<title>BBQ FIRST - Manual BBQ</title>
<!--
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
			<a href="manual_bbq.php" id="back" class="nav_txt">
				Back	
			</a> 
			
	</div>
	!-->
<?php include('navheader.html'); ?>
<script>
function linktoteam(key, yr)
{
	location.href = 'team_info.php?tem=' + key + "&year=" +yr;
}
function subform()
{
	document.getElementById("yrs").submit();
}
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45234838-2', 'auto');
  ga('send', 'pageview');

</script>
<table class="stats">
<tr class="val">
	<td class="stat">BBQ</td>
	<td class="val"><?php echo $bbq; ?></td>
</tr>
<tr class="val">
	<td class="stat">BBQ PDQ</td>
	<td class="val"><?php echo $bbqpdq; ?></td>
</tr>
</table>
<br>
<table class="region">
<?php
if($alltems || $topten)
{
?>
<th class="region">Rank</th>
<?php
}
?>
<th class="region">Team #</th>
<th class="region">Team</th>
<th class="region">Blue Banners</th>
<th class="region">First Year</th>
<?php
if(!$alltems && !$topten)
{
	foreach ($_GET as $key => $value) {
		// Do something with $key and $value
		if($value != "" && $value != null)
		{
		$qq = "SELECT team_info.team_num, team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				WHERE team_info.`team_num`='$value'";
		$sqli = $mysqli->query($qq);
		$row = mysqli_fetch_assoc($sqli);
		
	//if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	//{
	
	$vars = array($row["team_num"], 2015);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace(" ","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoteam(<?php echo $jsvars;?>)'>
	<td class="region"><?php echo $row["team_num"];?></td>
	<td class="region"><?php if($row['nickname']!=""){echo $row['nickname'];}else{echo "N/A";}?></td>
	<td class="region"><?php if($row['cmp']!="" || $row['cmp']!=null){echo $row['cmp'];}else{echo "0";};?></td>
	<td class="region"> <?php if($row['rookie']!=""){echo $row["rookie"];}else{echo "N/A";}?> </td>
	</tr>
<?php  	
	//} else {
?>
<!--
	<tr class="regions">
	<td class="region"><?php echo "";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"> <?php echo "N/A";?> </td>
	</tr>!-->
<?php
	//}

	}
}
?>

<?php
}
else if($alltems && !$topten)
{
	//for($val=1;$val<=$top;$val++)
	//{
	$rnk=1;
		// Do something with $key and $value
	//if($val != "" && $val != null)
	//{
		//$sqli = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$val' LIMIT 1");
		$qq = "SELECT team_info.team_num, team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				ORDER BY `2015`.`cmp` DESC, team_info.rookie DESC";
		$sqli = $mysqli->query($qq);
		//$row = mysqli_fetch_assoc($sqli);
	//$sqli = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` ORDER BY `banners` DESC, `team_num` ASC");
	while($row = mysqli_fetch_array($sqli))
	{
		
	//if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	//{
	$vars = array($row["team_num"], 2015);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace(" ","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoteam(<?php echo $jsvars;?>)'>
	<td class="region"><?php echo $rnk;?></td>
	<td class="region"><?php echo $row["team_num"];?></td>
	<td class="region"><?php if($row['nickname']!=""){echo $row['nickname'];}else{echo "N/A";}?></td>
	<td class="region"><?php if($row['cmp']!="" || $row['cmp']!=null){echo $row['cmp'];}else{echo "0";};?></td>
	<td class="region"> <?php if($row['rookie']!=""){echo $row["rookie"];}else{echo "N/A";}?> </td>
	</tr>
<?php  	
$rnk++;
	//} else {
?>
<!--
	<tr class="regions">
	<td class="region"><?php echo "";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"> <?php echo "N/A";?> </td>
	</tr> !-->
<?php
	//}
	//}
	}
//}
?>

<?php
}
else if($topten)
{
	//for($val=1;$val<=$top;$val++)
	//{
	$rnk=1;
		// Do something with $key and $value
	//if($val != "" && $val != null)
	//{
		//$sqli = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$val' LIMIT 1");
		$qq = "SELECT team_info.team_num, team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				ORDER BY `2015`.`cmp` DESC, team_info.rookie DESC
				LIMIT 10";
		$sqli = $mysqli->query($qq);
		//$sqli = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` ORDER BY `banners` DESC, `team_num` ASC LIMIT 10");
		while($row = mysqli_fetch_array($sqli))
		{
		
	//if($row['nickname']!="" && $row['team_num']!="" && $row['rookie']!="")
	//{
	
	$vars = array($row["team_num"], 2015);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace(" ","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoteam(<?php echo $jsvars;?>)'>
	<td class="region"><?php echo $rnk;?></td>
	<td class="region"><?php echo $row["team_num"];?></td>
	<td class="region"><?php if($row['nickname']!=""){echo $row['nickname'];}else{echo "N/A";}?></td>
	<td class="region"><?php if($row['cmp']!="" || $row['cmp']!=null){echo $row['cmp'];}else{echo "0";};?></td>
	<td class="region"> <?php if($row['rookie']!=""){echo $row["rookie"];}else{echo "N/A";}?> </td>
	</tr>
<?php  	
$rnk++;
	//} else {
?>
<!--
	<tr class="regions">
	<td class="region"><?php echo "";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"><?php echo "N/A";?></td>
	<td class="region"> <?php echo "N/A";?> </td>
	</tr> !-->
<?php
	//}
	//}
	}
//}
}

?>
	

</table>
 </div>

<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>

</body>
</html>