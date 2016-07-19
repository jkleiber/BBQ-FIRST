<?php
	//Script that adds new events to BBQ FIRST
	include("../connect.php");
	
	$year = date("Y");
	$week = date("W"); 
	
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$year."?X-TBA-App-Id=justin_kleiber:event_scraper:3");
	$regional = json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	
	$week_query = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `year`='$year' AND `sponsored`='official' ORDER BY `week` ASC LIMIT 1";
	$week_result = $mysqli->query($week_query);
	$week_row = $week_result->fetch_assoc();
	$start_week = $week_row['week'];
	
	$new_keys = "";
	
	foreach($regional as $r)
	{
		$key = $r['key'];
		$name = $r['name'];
		$event_year = $r['year'];
		
		$sdate = $r['start_date'];
		$date = new DateTime($sdate);
		$week = $date->format("W");
		
		$sponsored = $r['event_type'] < 5 ? "official" : "unofficial";
		
		$yearweek = $week - $start_week + 1;
		$yearweek = $sponsored == "unofficial" ? "cmp" : $yearweek;
		
		$short_name = $r['short_name'];
		
		if(!doesEventInfoExist($key,$mysqli) && $event_year == $year)
		{
			$new_keys .= $key;
			$new_keys .= "\n";
			
			$info_query = "INSERT INTO `regional_info` (`reg_name`,`reg_key`,`year`,`week`,`yearweek`,`sponsored`,`short_name`) VALUES ('$name','$key','$event_year','$week','$yearweek','$sponsored','$short_name')";
			$mysqli->query($info_query);
		}
	}
	
	$file = "./new_event_keys.txt";
	file_put_contents($file, $new_keys);
	
function doesEventInfoExist($key, $mysqli)
{
	$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$key' LIMIT 1";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	
	echo $row["COUNT(*)"];
	
	if($row["COUNT(*)"] > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>