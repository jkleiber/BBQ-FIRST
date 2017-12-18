<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	for($i=2005;$i<=2015;$i++)
	{
		$qi="DROP TABLE `bbqfirst_db`.`temp_".$i."`";
		$qii="CREATE TABLE `bbqfirst_db`.`temp_".$i."` LIKE `".$i."`";
		$qiii="INSERT `bbqfirst_db`.`temp_".$i."` SELECT * FROM `bbqfirst_db`.`".$i."`";
		$mysqli->query($qi) or trigger_error($mysqli->error."['$qi']");
		$mysqli->query($qii) or trigger_error($mysqli->error."['$qii']");
		$mysqli->query($qiii) or trigger_error($mysqli->error."['$qiii']");
		
	}
		$queries = array();
		$queries[]="DROP TABLE `bbqfirst_db`.`temp_regional_data`";
		$queries[]=	"CREATE TABLE `bbqfirst_db`.`temp_regional_data` LIKE `bbqfirst_db`.`regional_data`";
		$queries[]=	"INSERT `bbqfirst_db`.`temp_regional_data` SELECT * FROM `bbqfirst_db`.`regional_data`";
		$queries[]=	"DROP TABLE `bbqfirst_db`.`temp_regional_info`";
		$queries[]=	"CREATE TABLE `bbqfirst_db`.`temp_regional_info` LIKE `bbqfirst_db`.`regional_info`";
		$queries[]=	"INSERT `bbqfirst_db`.`temp_regional_info` SELECT * FROM `bbqfirst_db`.`regional_info`";
		$queries[]=	"DROP TABLE `bbqfirst_db`.`temp_team_info`";
		$queries[]=	"CREATE TABLE `bbqfirst_db`.`temp_team_info` LIKE `bbqfirst_db`.`team_info`";
		$queries[]=	"INSERT `bbqfirst_db`.`temp_team_info` SELECT * FROM `bbqfirst_db`.`team_info`";
		$queries[]=	"DROP TABLE `bbqfirst_db`.`temp_awards`";
		$queries[]=	"CREATE TABLE `bbqfirst_db`.`temp_awards` LIKE `bbqfirst_db`.`awards`";
		$queries[]=	"INSERT `bbqfirst_db`.`temp_awards` SELECT * FROM `bbqfirst_db`.`awards`";
		
		foreach($queries as $q)
		{
			$mysqli->query($q) or trigger_error($mysqli->error."['$q']");
		}
		
		$user="bbquser";
		$pass="bbqpass";
		$db="bbqfirst_db";
	
		$backupfile = "C:/Users/Justin/Dropbox/Programming/Website/BBQ_Dump/" . $db . date("Y-m-d-H-i-s") . '.sql';
		$command='C:/wamp/bin/mysql/mysql5.6.17/bin/mysqldump -u'.$user.' -p'. $pass .' '.$db. ' temp_2005 temp_2006 temp_2007 temp_2008 temp_2009 temp_2010 temp_2011 temp_2012 temp_2013 temp_2014 temp_2015 temp_team_info temp_regional_data temp_regional_info temp_awards > '.$backupfile;
		exec($command);
		
?>