<?php
	set_time_limit(0);
	ignore_user_abort(true);
	$mysqli = new mysqli('localhost','bbquser','bbqpass', "test");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$clear = $mysqli->query("TRUNCATE TABLE `test`.`regionaldata`");
		
	if(!$clear)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}
	
	for($i=2005;$i<=2015;$i++)
	{
	set_time_limit(0);
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$i."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a[name],$b[name]);});
	
	foreach($regional as $r)
	{
		set_time_limit(0);
		$tems = 0;
		$bans = 0;
		$yrs = 0;
		$saucer = 0;
		
		$name = $mysqli->real_escape_string($r[name]);
		$key = $mysqli->real_escape_string($r[key]);
		$o = $r[official];
		$yr = $r[year];
		if($o == "true")
		{
			$off = "official";
		}
		else
		{
			$off = "unofficial";
		}
		$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $r[key] ."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
		$teamlist=json_decode($teams,true);
		usort($teamlist,function($a,$b) {return strnatcasecmp($a[team_number],$b[team_number]);});
		
		foreach($teamlist as $t)
		{
			set_time_limit(0);
			$n = $t[team_number];
			$sql = $mysqli->query("SELECT * FROM `test`.`bbq_new` WHERE `team_num` = '$n' LIMIT 1");
			$row = mysqli_fetch_assoc($sql);
			
			$tems++;
			$bans += $row[$yr];
			//$y=2015-$yr;
			$yrs += ($row['years']);
			
			$ssql = $mysqli->query("SELECT * FROM `test`.`sauce_new` WHERE `team_num` = '$n' LIMIT 1");
			$row = mysqli_fetch_assoc($ssql);
			$saucer += $row[$yr];
		}
		$bbq = 0;
		$sauc = 0;
		if($tems!=0)
		{
			$bbq = $bans/$tems;
			$sauc = $saucer/$tems;
		}
		$query = "INSERT INTO `test`.`regionaldata` (`reg_name`, `banners`, `teams`, `years`, `bbq`, `sauce`, `sponsored`, `reg_key`, `year`) VALUES ('$name', '$bans', '$tems', '$yrs', '$bbq', '$sauc', '$off', '$key', $yr)";
		$result = $mysqli->query($query);
		
		if(!$result)
		{
			die('Error: ' . mysqli_error($mysqli)); 
		}
		}
		
	}

?>