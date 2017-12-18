<?php
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	
	include("connect.php");
	
	$waitlist_tickets = 1;
	$limit = 0;
	
	for($i=10;;$i++)
	{
		$str = file_get_contents("http://www.thebluealliance.com/api/v2/teams/". $i ."?X-TBA-App-Id=justin_kleiber:total_teams_in_frc:1");
		$tems = json_decode($str,true);
		if(empty($tems))
		{
			$str = file_get_contents("http://www.thebluealliance.com/api/v2/teams/". ($i-1) ."?X-TBA-App-Id=justin_kleiber:total_teams_in_frc:1");
			$tems = json_decode($str,true);
			$c = count($tems);
			$limit = $tems[$c-1]["team_number"];
			break;
		}
	} 
	
	for($team=1;$team<$limit;$team++)
	{
		$last = 2016;
		$query = "SELECT * FROM `team_info` WHERE team_num='$team' LIMIT 1";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		
		$rookie = $row['rookie'];
		
		if($rookie>0 && (2016-$rookie)>=$row['years'])
		{
			for($year=2016;$year>=$rookie;$year--)
			{
				$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc". $team ."/".$year."/events?X-TBA-App-Id=justin_kleiber:event_scraper:1");
				$n=json_decode($string,true);
				
				foreach($n as $event)
				{
					if(strpos($event['key'],"cmp") !== false || strpos($event['key'],"arc") !== false || strpos($event['key'],"cars") !== false || strpos($event['key'],"carv") !== false || strpos($event['key'],"cur" ) !== false || strpos($event['key'],"gal" ) !== false || strpos($event['key'],"hop" ) !== false || strpos($event['key'],"new" ) !== false || strpos($event['key'],"tes" ) !== false)
					{
						$waitlist_tickets += (2016 - $year);
						$last = $year;
						$year = 0;
						break;
					}
				}
				
				if($year == $rookie)
				{
					$waitlist_tickets += 2016 - $rookie;
					$last = 0;
				}
			}
		}
		
		$str = "After Team " . $team . " Tickets " . $waitlist_tickets . " last attended in " . $last;
		file_put_contents("money_money_b0ss.txt",$str);
	}
	
	echo "Total Raffle Tickets: " . $waitlist_tickets;
	echo "<br>";
	
	$chance = 1 / $waitlist_tickets;
	
	echo "Our Chances: " . $chance;
	
?>