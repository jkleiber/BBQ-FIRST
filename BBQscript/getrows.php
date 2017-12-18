<?php

	$mod=$_GET['mode'];
	
	$mysqli = new mysqli('localhost','bbqfrcx1_bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	if($mod=="ri")
	{
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`regional_info` WHERE 1";
	}
	else if($mod=="ti")
	{
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`team_info` WHERE 1";
	}
	else if($mod=="rd")
	{
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`regional_data` WHERE 1";
	}
	else if($mod=="aw")
	{
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`awards` WHERE 1";
	}
	else
	{
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`".$mod."` WHERE 1";
	}
	$res=$mysqli->query($query);
	$row=mysqli_fetch_assoc($res);
	$c = $row['COUNT(*)'];
	
	echo $c;
?>