<?php
session_start();
if($_SESSION['code']=="bbqfirstadmin")
{

	set_time_limit(0);
	ignore_user_abort(true);
	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$clear = $mysqli->query("TRUNCATE TABLE `bbqfrcx1_db`.`regional_info`");
		
	if(!$clear)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}
	
	for($i=2005;$i<=2017;$i++)
	{
		$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$i."?X-TBA-App-Id=justin_kleiber:event_scraper:3");
		$regional = json_decode($string,true);
		usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
		
		foreach($regional as $r)
		{
			$key = $r['key'];
			$name = $r['name'];
			$event_year = $r['year'];
			
			$sdate = $r['start_date'];
			$date = new DateTime($sdate);
			$week = $date->format("W");
			
			$sponsored = $r['event_type'] < 5 ? "official" : "unofficial";
			
			$yearweek = "0";
			$yearweek = $sponsored == "unofficial" ? "cmp" : $yearweek;
			
			$short_name = $r['short_name'];	
			
			$info_query = "INSERT INTO `regional_info` (`reg_name`,`reg_key`,`year`,`week`,`yearweek`,`sponsored`,`short_name`) VALUES ('$name','$key','$event_year','$week','$yearweek','$sponsored','$short_name')";
			$mysqli->query($info_query);
			
		}
	
	}
		
	
} else {
 header("Location: ../../logout.php");
 }
?>