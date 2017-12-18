<html>
<?php

	set_time_limit(0);

	$team_list = [];
	$real_list = [];
	
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/2014?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a[name],$b[name]);});
	foreach($regional as $r)
	{
		$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $r[key] ."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
		$tem=json_decode($teams,true);
		usort($tem,function($a,$b) {return strnatcasecmp($a[name],$b[name]);});
	
		foreach($tem as $t)
		{
			$team_list[] = $t[team_number];
		}
	}
	$real_list = array_unique($team_list);
?>

<a href="downloaddata.php">Teams List Prepared</a>
</html>