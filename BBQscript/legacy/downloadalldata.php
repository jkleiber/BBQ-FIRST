<html>
<?php
	//ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);  
	$handle = mysql_connect('localhost','bbquser','bbqpass');
    mysql_select_db('test', $handle); 
	
	$limit = 0;
	
	
	$spaces = 0;
	
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
	//$limit = 50;
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
			set_time_limit(0);
			$b_count = array();
			$year = 0;
			$bi = 0;
			$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
			$info=json_decode($string,true);
			
			$firstthru = true;
			
			if($info["nickname"] != null && $info["nickname"] != "")
			{
				$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
				$time=json_decode($part,true);
				$c = count($time);
				
				echo $info["nickname"];
				echo "<br>";
				$ry = 2015;
				
				foreach($time as $tim)
				{
					if($firstthru)
					{
						$ry=$tim;
					}
					if($tim > 2005)
					{
						$year = 0; //dont reset unless past 2005
					}
					if($tim > 2005 && $firstthru)
					{
						$diff = $tim - 2005;
						$bi = $diff-1;
						for($in = 0; $in < $diff; $in++)
						{
							$b_count[] = 0;
						}
						
					}
					$ev = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc".$t."/".$tim."/events?X-TBA-App-Id=justin_kleiber:team_info_scraper:2");
					$event=json_decode($ev,true);
					foreach($event as $e)
					{
						if($e["official"] == 'true')
						{
						$awards = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc". $t ."/event/". $e["key"] ."/awards?X-TBA-App-Id=justin_kleiber:team_info_scraper:2");
						$a=json_decode($awards,true);
						
						foreach($a as $b)
						{
							if($b['award_type'] == 0 || $b['award_type'] == 1)
							{
								$year++;
							}
						}
						}
					}
					
					if($tim == 2005) //If we are ready to collect values 
					{
						$b_count[] = $year;
					}
					if($tim > 2005) //If we are ready to sum values 
					{
						$b_count[] = ($year + $b_count[$bi]);
						$bi++;
					}
					
					$firstthru = false;
					
				}
				
				$name = mysql_real_escape_string($info["nickname"]);
				if($info["rookie_year"] != null && $info["rookie_year"] !=0)
				{
					$yr = $info["rookie_year"];
				}
				else
				{
					$yr=$ry;
				}
				$bb = end($b_count);
				
				$sql = mysql_query("INSERT INTO `test`.`BBQ_new` (`team_num`, `banners`, `nickname`, `rookie`, `years`,`2015`,`2014`,`2013`,`2012`,`2011`,`2010`,`2009`,`2008`,`2007`,`2006`,`2005`) VALUES ('$t', '$bb', '$name', '$yr', '$c', '$bb', '$b_count[9]', '$b_count[8]', '$b_count[7]', '$b_count[6]', '$b_count[5]', '$b_count[4]', '$b_count[3]', '$b_count[2]', '$b_count[1]', '$b_count[0]')");
		
			
				if (!$sql) 
				{ 
					die('Error: ' . mysql_error($handle)); 
				}
			}
		
	}
	mysql_close($handle);
?>
</html>