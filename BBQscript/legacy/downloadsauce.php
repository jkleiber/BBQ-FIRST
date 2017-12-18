<html>
<?php
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
	//$limit = 50;
	if (mysqli_connect_errno($handle)) 
    { 
        echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
    } 
	
	$clear = mysql_query("TRUNCATE TABLE `SAUCE_new`");
	
	if (!$clear) 
	{ 
		die('Error: ' . mysql_error($handle)); 
	}
    
	for($t=1;$t<=$limit;$t++)
	{
			set_time_limit(0);
			$b_count = array();
			$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
			$info=json_decode($string,true);
			
				$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
				$time=json_decode($part,true);
				$c = count($time);
				$banners=0;
				$oldbanners=0;
				
				for($tim=2005; $tim<=2015; $tim++)
				{
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
								$banners++;
							}
						}
						}
					}
					$b_count[]=$banners;
				}
				
				$sql = mysql_query("INSERT INTO `test`.`SAUCE_new` (`team_num`, `post2005banners`,`2015`,`2014`,`2013`,`2012`,`2011`,`2010`,`2009`,`2008`,`2007`,`2006`,`2005`) VALUES ('$t', '$banners', '$b_count[10]', '$b_count[9]', '$b_count[8]', '$b_count[7]', '$b_count[6]', '$b_count[5]', '$b_count[4]', '$b_count[3]', '$b_count[2]', '$b_count[1]', '$b_count[0]')");
		
			
				if (!$sql) 
				{ 
					die('Error: ' . mysql_error($handle)); 
				}
		
	}
	mysql_close($handle);
?>
</html>