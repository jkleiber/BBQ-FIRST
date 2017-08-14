<?php

	//Include this to connect to DB and get rid of notices
	include("read_ini.php");
	error_reporting(E_ALL ^ E_NOTICE);
	$host = $ini['dbhost'];
	$user = $ini['dbuser'];
	$pass = $ini['dbpass'];
	$name = $ini['dbname'];
	$mysqli = new mysqli($host,$user,$pass,$name);
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	//Set the max year to this year, or the next year depending on the current date
	$month = date('n');
	$day = date('j');
	$current_year = date('Y');
	
	$max_year = $current_year;
	
	if($month > 9 || $month == 9 && $day >= 20)
	{
		$max_year = $current_year + 1;
	}
	
?>