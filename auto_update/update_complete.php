<?php
	include("connect.php");
	
	$year = date("Y");
	$month = date("M");
	$day = date("d");
	
	$hour = date("G");
	$minute = date("i");
	$zone = date("T");
	
	$time_str = $month . "/" . $day . "/" . $year . " at " . $hour . ":" . $minute . " " . $zone;
	//echo $time_str;
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Last Updated: ".$time_str."' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
?>