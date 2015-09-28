<?php

include('connect.php');
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query) or trigger_error($mysqli->error."[$query]");

$row=mysqli_fetch_assoc($sqli);
$fleg=$row['flag'];
if($fleg=="on_minutes" || $fleg=="on_hours")
{
include 'downtime.php';
}
else
{
	$arr = array();
	for($year = 2005; $year < 2017; $year++)
	{
	$querys = "SELECT `regional_info`.`year`, `regional_data`.`ribs` ,`regional_info`.`reg_key`, `regional_data`.`reg_key`
			 FROM `bbqfrcx1_db`.`regional_data` 
			 INNER JOIN `regional_info`
			 ON `regional_data`.`reg_key` = `regional_info`.`reg_key`
			 WHERE `regional_info`.`year`='$year'
			 ORDER BY `regional_data`.`ribs`+0 DESC
			 LIMIT 1"; 
			 
	$results = $mysqli->query($querys) or trigger_error($mysqli->error."[$querys]");
	$row = mysqli_fetch_assoc($results);
	
	$arr[] = $row['ribs'];
	}
	echo json_encode($arr);
}
?>