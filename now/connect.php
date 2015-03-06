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
	
?>