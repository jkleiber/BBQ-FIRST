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
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Championship Division Rankings</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">

<script type="text/javascript" src="jquery-1.11.1.min.js"></script> 
<script type="text/javascript" src="jquery.tablesorter.js"></script> 
</head>

<script>
$(document).ready(function() 
{ 
    $("#divstable").tablesorter({cssAsc:"regionSortUp",cssDesc:"regionSortDown",cssHeader:"regionSort"}); 
} 
); 

function linktoevent(key, yr)
{
	location.href = 'event_info.php?key=' + key + "&year=" +yr;
}
</script>

<body onload="load()">
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	
	<h1>Championship Division Comparisons</h1>
	<h5>You can sort columns by clicking the headers</h5>
	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	if($_GET['year'])
	{
		$year=$_GET['year'];
	}
	else
	{
		$year=2015;
	}
	
	$qq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
		FROM regional_data
		INNER JOIN regional_info
		ON regional_data.reg_key=regional_info.reg_key
		WHERE regional_info.reg_key = '2015gal' OR regional_info.reg_key = '2015carv' OR regional_info.reg_key = '2015hop' OR regional_info.reg_key = '2015cur' OR regional_info.reg_key = '2015arc' OR regional_info.reg_key = '2015new' OR regional_info.reg_key = '2015tes' OR regional_info.reg_key = '2015cars'";
		
	$result = $mysqli->query($qq);
	?>
	
	<table id="divstable" class="region" style="width:90%">
	<thead>
		<th class="region">Division Name</th>
		<th class="region">BBQ</th>
		<th class="region">SAUCE</th>
		<th class="region">BRIQUETTE</th>
		<th class="region">RIBS</th>
		<th class="region">Teams</th>
	</thead>
	<tbody>
	<?php
	while($r = $result->fetch_array(MYSQLI_ASSOC))
	{
		
		
		$vars = array($r["reg_key"], $r['year']);
		$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
		$jsvars = str_replace("[","", $jsvars);
		$jsvars = str_replace("]","", $jsvars);
		
		?>
			<tr class="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
				<td><?php echo $r['reg_name']; ?></td>
				<td><?php echo round($r['bbq'], 5); ?></td>
				<td><?php echo round($r['sauce'], 5); ?></td>
				<td><?php echo round($r['briquette'], 5); ?></td>
				<td><?php echo round($r['ribs'], 5); ?></td>
				<td><?php echo round($r['teams'], 5); ?></td>
			</tr>
		<?php
	}
	?>
	</tbody>
	</table>


<?php
}
?>