<?php
	for($i=10;;$i++)
	{
		$str = file_get_contents("http://www.thebluealliance.com/api/v2/teams/". $i ."?X-TBA-App-Id=justin_kleiber:total_teams_in_frc:1");
		$tems = json_decode($str,true);
		if(empty($tems))
		{
			$str = file_get_contents("http://www.thebluealliance.com/api/v2/teams/". ($i-1) ."?X-TBA-App-Id=justin_kleiber:total_teams_in_frc:1");
			$tems = json_decode($str,true);
			$c = count($tems);
			echo $tems[$c-1]["team_number"];
			break;
		}
	}
	$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc624/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
	$time=json_decode($part,true);
	$c = count($time);
	echo "<br>";
	echo $c;
?>