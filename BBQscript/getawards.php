<?php
session_start();
ini_set('max_execution_time', 0);
set_time_limit(0);
	if($_SESSION['code']=="bbqfirstadmin")
	{
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$clear = $mysqli->query("TRUNCATE TABLE `bbqfrcx1_db`.`awards`");
		
		if(!$clear)
		{
			die('Error: ' . mysqli_error($mysqli)); 
		}
		
		for($i=1992;$i<=2016;$i++)
		{
			set_time_limit(0);
			$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$i."?X-TBA-App-Id=justin_kleiber:event_scraper:3");
			$regional=json_decode($string,true);
			usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	
			foreach($regional as $r)
			{
				//usort($awardslist, function($a,$b) {return strnatcasecmp($a['award_type'],$b['award_type']);});
				
				if($r['official']=="true")
				{
					$keyr = $r['key'];
					$aw = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$keyr."/awards?X-TBA-App-Id=justin_kleiber:team_scraper:3");
					$awardslist=json_decode($aw,true);
					
				foreach($awardslist as $a)
				{
					
					if($a["award_type"] < 2)
					{
						$type = $a["award_type"];
						$reg_key = $a['event_key'];
						$name = $name = $mysqli->real_escape_string($a['name']);;
						
						foreach($a['recipient_list'] as $key => $an)
						{
							$tem=$an['team_number'];
							$query = "INSERT INTO `bbqfrcx1_db`.`awards` (`reg_key`, `name`, `award_id`, `team_num`, `year`) VALUES ('$reg_key', '$name', '$type', '$tem', '$i')";
							$mysqli->query($query);
						}
					}
					
					
				}
				}
			}
		}
		
		$query = "INSERT INTO `bbqfrcx1_db`.`awards` (`reg_key`, `name`, `award_id`, `team_num`, `year`) VALUES ('', 'Regional Winner', '1', '233', '2005');
				INSERT INTO `bbqfrcx1_db`.`awards` (`reg_key`, `name`, `award_id`, `team_num`, `year`) VALUES ('', 'Regional Winner', '1', '1592', '2005');
				INSERT INTO `bbqfrcx1_db`.`awards` (`reg_key`, `name`, `award_id`, `team_num`, `year`) VALUES ('', 'Regional Winner', '1', '179', '2005');";
		$mysqli->query($query);
}
else
{
	header("Location: ../../logout.php");
}
?>