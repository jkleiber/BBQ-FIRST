<?php
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	
	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$query = "SELECT `reg_key` FROM `bbqfrcx1_db`.`regional_info`";
	$result = $mysqli->query($query);
	
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$key = $row['reg_key'];
		
		$string = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$key."?X-TBA-App-Id=justin_kleiber:short_name_updater:1");
		$event = json_decode($string,true);
		
		$short_name = $event['short_name'];
		
		$query = "UPDATE `bbqfrcx1_db`.`regional_info` SET `short_name`='$short_name' WHERE `reg_key`='$key'";
		$mysqli->query($query);
	}
?>