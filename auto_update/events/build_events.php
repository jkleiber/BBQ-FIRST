<?php
function build_event($key, $current_year, $mysqli)
{	
	//Get Event Information
	$q = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$key'";
	$sql = $mysqli->query($q)  or trigger_error($mysqli->error."[$q]");
	$row = $sql->fetch_assoc();
	
	$week = $row['yearweek'];
	$year = $row['year'];
	$real_event = $row['sponsored'];

	
	//Get List of Teams
	$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$key."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:2");
	$teamlist = json_decode($teams,true);
	usort($teamlist,function($a,$b) {return strnatcasecmp($a[team_number],$b[team_number]);});
	
	//Initialize values to 0
	$teams=0;
	$blue_banners=0;
	$sauce=0;
	$briquette=0;
	$ribs=0;
	$years=0;
	
	//Determine what week we should be pulling blue banner data from
	if(strpos($week,"cmp")===false)
	{
		$week_column = "wk" . ($week-1);
	}
	else
	{
		if(strcmp($real_event,"official")==0) //Championship event, and other official events in the last weeks
		{
			if($year >= 2016)//2016 was the first year to have 8 weeks
			{
				$week_column = "wk8"; 
			}
			else
			{
				$week_column = "wk7";
			}
		}
		else
		{
			$week_column = "cmp";
		}
	}
	
	$champs = strpos($key,"cmp");
	if($champs===true)
	{
		foreach($teamlist["alliances"] as $all) 
		{
			foreach ($all['picks'] as $key => $value) 
			{
				$team_number = str_replace("frc","", $value);
				
				$data = getTeamBanners($team_number, $year, $week_column, $mysqli);
				$blue_banners += $data['blue_banners'];
				$sauce += $data['sauce'];
				$briquette += $data['briquette'];
				$ribs += $data['ribs'];
				$years += $data['years'];
				
				$teams++;
			}
		}
	}
	else
	{
		foreach($teamlist as $t)
		{
			$team_number = $t['team_number'];
			
			$data = getTeamBanners($team_number, $year, $week_column, $mysqli);
			$blue_banners += $data['blue_banners'];
			$sauce += $data['sauce'];
			$briquette += $data['briquette'];
			$ribs += $data['ribs'];
			$years += $data['years'];
			
			$teams++;
		}
	}
	
	//Get ready to calculate the data that will go into the database
	$stat_BBQ = 0;
	$stat_SAUCE = 0;
	$stat_BRIQUETTE = 0;
	$stat_RIBS = 0;
	$stat_BBQPDQ = 0;
	
	if($teams != 0)
	{
		$stat_BBQ = $blue_banners / $teams;
		$stat_SAUCE = $sauce / $teams;
		$stat_BRIQUETTE = $briquette / $teams;
		$stat_RIBS = $ribs / $teams;
		
		$stat_BBQPDQ = $stat_BBQ / $years;
	}
	else
	{
		$stat_BBQ = 0;
		$stat_SAUCE = 0;
		$stat_BRIQUETTE = 0;
		$stat_RIBS = 0;
		
		$stat_BBQPDQ = 0;
	}
	
	//Now that values are calculated, we need to decide
	//if we want to UPDATE an event or create a new one.
	if(doesEventExist($key, $mysqli))
	{
		$query = "UPDATE `bbqfrcx1_db`.`regional_data` SET `teams`='$teams', `years`='$years', `banners`='$blue_banners',`bbq`='$stat_BBQ',`bbq_pdq`='$stat_BBQPDQ',`sauce`='$stat_SAUCE',`briquette`='$stat_BRIQUETTE',`ribs`='$stat_RIBS' WHERE `reg_key`='$key'";
	}
	else
	{
		$query = "INSERT INTO `bbqfrcx1_db`.`regional_data` (`reg_key`, `teams`, `years`, `banners`, `bbq`, `bbq_pdq`, `sauce`, `ribs`, `briquette`) VALUES ('$key', '$teams', '$years', '$blue_banners', '$stat_BBQ', '$stat_BBQPDQ', '$stat_SAUCE', '$stat_RIBS', '$stat_BRIQUETTE')";
	}
	
	//Event = gud b0ss
	$mysqli->query($query) or trigger_error($mysqli->error."[$query]");
}

function getTeamBanners($team_number, $year, $week_column, $mysqli)
{
	//Filter week 8 stuff, because that was added in 2016
	$briquette_week = $week_column;
	$rib_week = $week_column;
	if($year < 2020 && strcmp($week_column,"wk8")==0)
	{
		$briquette_week = "wk7";
	}
	if($year < 2017 && strcmp($week_column,"wk8")==0)
	{
		$rib_week = "wk7";
	}
	
	//set return vars to 0
	$blue_banners = 0;
	$sauce = 0;
	$briquette = 0;
	$ribs = 0;
	$years = 0;
	
	//Time to get blue banner data
	$team_raw = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`$year` WHERE `team_num` = '$team_number' LIMIT 1")or trigger_error($mysqli->error);
	$row = $team_raw->fetch_assoc();
	$blue_banners = $row[$week_column];
	
	//Get 2005 banners as reference point for SAUCE calculations
	$team_2005 = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`2005` WHERE `team_num` = '$team_number' LIMIT 1") or trigger_error($mysqli->error);
	$row_2005 = $team_2005->fetch_assoc();
	$sauce = ($blue_banners - $row_2005['wk0']);
	
	//BRIQUETTE Calculations
	if(($year-4)>=2005)
	{
		$range = ($year-4);
		$briquette_query = "SELECT * FROM `bbqfrcx1_db`.`$range` WHERE `team_num` = '$team_number' LIMIT 1";
		$team_briquette_result = $mysqli->query($briquette_query) or trigger_error($mysqli->error."[$briquette_query]");
		$team_briquette_row = $team_briquette_result->fetch_assoc();
		
		$briquette = $blue_banners-$team_briquette_row[$briquette_week];
	}
	else
	{
		$briquette = 0;
	}
	
	//RIBS Calculations
	if(($year-1)>=2005)
	{
		$last_year=($year-1);
		$team_ribs = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`$last_year` WHERE `team_num` = '$team_number' LIMIT 1") or trigger_error($mysqli->error);
		$team_ribs_row = $team_ribs->fetch_assoc();
		
		$ribs = $blue_banners - $team_ribs_row[$rib_week];
	}
	else
	{
		$ribs = 0;
	}
	
	//Get years participated in FRC
	$team_experience_query = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`team_info` WHERE `team_num` = '$team_number' LIMIT 1") or trigger_error($mysqli->error);
	$team_experience_result = $team_experience_query->fetch_assoc();
	$years = $team_experience_result['years'];
	
	$data = [];
	$data['blue_banners'] = $blue_banners;
	$data['sauce'] = $sauce;
	$data['briquette'] = $briquette;
	$data['ribs'] = $ribs;
	$data['years'] = $years;
	
	return $data;
}

function doesEventExist($key, $mysqli)
{
	$query = "SELECT COUNT(*) FROM `bbqfrcx1_db`.`regional_data` WHERE `reg_key`='$key' LIMIT 1";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	
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