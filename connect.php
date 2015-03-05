<?php

	//Include this to connect to DB and get rid of notices
	include("read_ini.php");
	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli($ini['dbhost'],$ini['dbuser'],$ini['dbpass'], $ini['dbname']);
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
?>