<?php
error_reporting(E_ALL ^ E_NOTICE);
include('connect.php');
?>
<html>
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Manual BBQ</title>
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
			
	</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45234838-2', 'auto');
  ga('send', 'pageview');

</script>
<h1>The FRC Blue Banner Top 10 (with ties)</h1>
<table class="region">
<th class="region">Rank</th>
<th class="region">Team #</th>
<th class="region">Team</th>
<th class="region">Blue Banners</th>
<th class="region">First Year</th>
<?php
$rnk=1;

		$qq = "SELECT team_info.years, team_info.rookie, `2015`.cmp, GROUP_CONCAT(team_info.nickname ORDER BY `2015`.cmp SEPARATOR ',') as 'nickname',GROUP_CONCAT(team_info.team_num ORDER BY `2015`.cmp SEPARATOR ',') as 'team_num'
FROM `2015`
INNER JOIN team_info
ON `2015`.team_num=team_info.team_num
GROUP BY `2015`.cmp
ORDER BY `2015`.`cmp` DESC, team_info.rookie DESC
LIMIT 10";
		$sqli = $mysqli->query($qq);
		while($row = mysqli_fetch_array($sqli))
		{
	
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
	
	}

?>
</table>