<?php
	//Script that gets all event keys and lists them in a file
	include("../connect.php");	
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Fetching All Events...' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
	
	$year = date("Y");
	$week = date("W");
	
	$query = "SELECT `reg_key`,`year` FROM `regional_info` WHERE `year`='$year' AND `week`>='$week'";
	$result = $mysqli->query($query);
	
	$keys = "";
	
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$keys .= $row['reg_key'];
		$keys .= "\n";
	}
	
	$newEvents = fopen("./new_event_keys.txt","r+");
	while(!feof($newEvents))
	{
		$key = fgets($newEvents);
		$keys .= $key;
		$keys .= "\n";
	}
	
	$file = "./event_keys.txt";
	file_put_contents($file, $keys);
?>