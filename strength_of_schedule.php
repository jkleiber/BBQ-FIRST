<?php

	include("connect.php");
	
	$regionals = ["2016txho","2016txsa","2016alhu"];
	
	$banners = 0;
	$old_banners = 0;
	
	foreach($regionals as $key)
	{
		$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $key ."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
		$teamlist=json_decode($teams,true);
		
		foreach($teamlist as $t)
		{
			$num = $t['team_number'];
			$query = "SELECT `wk0`,`cmp` FROM `2016` WHERE `team_num`=$num";
			$result = $mysqli->query($query);
			
			$row = $result->fetch_assoc();
			
			$banners += ($row['cmp'] - $row['wk0']);
		}
		
		echo $key . ": " . ($banners-$old_banners) . "<br>";
		$old_banners = $banners;
	}
	
	echo $banners;

?>