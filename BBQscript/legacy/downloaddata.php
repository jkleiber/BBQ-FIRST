<html>
<?php
	//ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);  
	$handle = mysql_connect('localhost','bbquser','bbqpass');
    mysql_select_db('test', $handle); 
	
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
	
	if (mysqli_connect_errno($handle)) 
    { 
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
    } 
	
	$clear = mysql_query("TRUNCATE TABLE `BBQ_new`");
	
	if (!$clear) 
	{ 
		die('Error: ' . mysql_error($handle)); 
	}
    
	for($t=1;$t<=$limit;$t++)
	{
	echo '$t';
	echo str_repeat(' ', ini_get('output_buffering'));
    flush();
		set_time_limit(0);
		$text = @file_get_contents('http://www.thebluealliance.com/team/'. $t .'/history');
		
		if($text != false)
		{
			set_time_limit(0);
			$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
			$info=json_decode($string,true);
			
			if($info["rookie_year"] != 0 && $info["nickname"] != null && $info["nickname"] != "")
			{
				$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
				$time=json_decode($part,true);
				$c = count($time);
	
				$win = substr_count($text, "Winner");
				$chair = substr_count($text, "Regional Chairman");
				$div_win = substr_count($text, "Division Champion");
			
				set_time_limit(0);
			
				$bb=$win+$chair+$div_win;
			
				$name = mysql_real_escape_string($info["nickname"]);
				$yr = $info["rookie_year"];
				$sql = mysql_query("INSERT INTO `test`.`BBQ_new` (`team_num`, `banners`, `nickname`, `rookie`, `years`) VALUES ('$t', '$bb', '$name', '$yr', '$c')");
		
			
				if (!$sql) 
				{ 
					die('Error: ' . mysql_error($handle)); 
				}
			}
		}
	}
	mysql_close($handle);
?>
</html>