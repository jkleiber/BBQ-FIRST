<?php

	//Script that gets all awards for later compilation
	
	$award_teams = "";
	
	$d = new DateTime();
	$start = $d->getTimestamp();

	include("../connect.php");
	$year = date("Y");
	$week = date("W");
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Automatically Updating Awards...' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
	
	$number_to_update = 200;
	
	$contents = file_get_contents("./event_keys.txt");
	$file = fopen("event_keys.txt","r+");
	$year = date("Y");
	
	$i=0;
	
	$num_lines=-1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
		$num_lines++;
	} 
	
	if($num_lines < $number_to_update)
	{
		$number_to_update = $num_lines;
	}
	
	echo $number_to_update;
	
	while($i<$number_to_update && !feof($file))
	{
		$e_key = fgets($file);
		
		//get rid of all the special formatting
		$e_key = str_replace(" ","",$e_key);
		$e_key = str_replace("\r","",$e_key);
		$e_key = str_replace("\n","",$e_key);
		
		$keys .= $e_key . "_";
		
		$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`awards` WHERE `reg_key`='$e_key' AND `year`='$year'";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		
		if($row['COUNT(*)'] < 4)
		{
			$award_str = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$e_key."/awards?X-TBA-App-Id=justin_kleiber:team_scraper:3");
			$awardsList=json_decode($award_str,true);
			
			foreach($awardsList as $a)
			{
				if($a["award_type"] < 2)
				{
					$type = $a["award_type"];
					$name = $mysqli->real_escape_string($a['name']);
					
					foreach($a['recipient_list'] as $key => $an)
					{
						$team = $an['team_number'];
						$query = "INSERT INTO `bbqfrcx1_db`.`awards` (`reg_key`, `name`, `award_id`, `team_num`, `year`) VALUES ('$e_key', '$name', '$type', '$team', '$year')";
						$mysqli->query($query);
						
						$award_teams .= $team . "\n";
					}
				}
			}
		}
		
		$i++;
	}
	//update list of remaining events
	$contents = str_replace("\n","_",$contents);
	$contents = str_replace($keys,"",$contents);
	$contents = str_replace("_","\n",$contents);
	file_put_contents("./event_keys.txt",$contents);
	
	//add the new teams to our list
	file_put_contents("award_teams.txt",$award_teams);
	
	$newDate = new DateTime();
	$elapsed = ($newDate->getTimestamp()) - $start;
	echo $elapsed;
?>