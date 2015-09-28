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
	$team = $_GET['t'];
	
	$arr = array();
	for($year = 2005; $year < 2017; $year++)
	{
		$querys = "SELECT `".$year."`.`cmp`, `".$year."`.`wk0`
				FROM `bbqfrcx1_db`.`".$year."`
				WHERE `".$year."`.`team_num`='".$team."'
				LIMIT 1"; 
				
		$results = $mysqli->query($querys) or trigger_error($mysqli->error."[$querys]");
		$row = mysqli_fetch_assoc($results);
		
		$arr[] = $row['cmp'] - $row['wk0'];
	}
	echo json_encode($arr);
}
?>