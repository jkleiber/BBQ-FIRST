<?php
	include('connect.php');
	$date = new DateTime();

	$query = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Automatic Update Tests in progress...  " . $date->getTimestamp() . "' WHERE `flag` = 'ndisplay'";
	
	//echo $query;
	
	$mysqli->query($query); 
	
	$log_file = "./log_file_" . $date->getTimestamp() .".txt";
	
	file_put_contents($log_file, "CRON Job successfully ran!");
?>