<?php
session_start();
		if($_SESSION['code']=="bbqfirstadmin")
		{


	ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	$limit = 0;
	
	$year=$_POST['year'];
	$week=$_POST['week'];
	
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

	$clear = $mysqli->query("TRUNCATE TABLE `bbqfirst_db`.`".$year."`");
	
	if(!$clear)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}
	for($t=1;$t<=$limit;$t++)
	{
		//echo $t . "<br>";
		$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
		$time=json_decode($part,true);
		
		$b_count = array();
		$lastwk = 0;
		$bbb = 0; 
		$wk=0;
		$bb = 0; //during weeks
		
		$tem = $mysqli->query("SELECT * FROM `bbqfirst_db`.`".($year-1)."` WHERE `team_num`='$t'");
		$tt = mysqli_fetch_assoc($tem);
		
		$glob = $tt['cmp']; //before specified year
		
		foreach($time as $tim)
		{
			if($tim==$year)
			{
				$ev = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc".$t."/".$year."/events?X-TBA-App-Id=justin_kleiber:team_info_scraper:2");
				$event=json_decode($ev,true);
				usort($event, function($a, $b) { //Sort the array using a user defined function
						return $a['start_date'] < $b['start_date'] ? -1 : 1; //Compare the scores
				});  
				foreach($event as $e)
				{
					$key = $e['key'];
					$que = "SELECT * FROM `bbqfirst_db`.`regional_info` WHERE `reg_key` = '$key' LIMIT 1";
					$res = $mysqli->query($que);
					$arr = mysqli_fetch_assoc($res);
					
					echo count($b_count) . "<br>";
					
					$wk = $arr['yearweek'];
					if($wk=="cmp")
					{
						$wk=8;
					}
					
					if($lastwk==0)
					{
						$lastwk=$wk;
						$b_count[] = $glob;
						$lim=$wk-1;
						for($ii=0;$ii<$lim;$ii++)
						{
							$b_count[] = $glob;
						}
					}
					else if($lastwk!=$wk && $lastwk!=0)
					{
						$b_count[] = $glob;
						$lim=$wk-count($b_count);
						for($ii=0;$ii<$lim;$ii++)
						{
							$b_count[] = $glob;
						}
					}
			
					if($wk<=$week)
					{
						if($e["official"] == 'true')
						{
							$awards = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc". $t ."/event/". $key ."/awards?X-TBA-App-Id=justin_kleiber:team_info_scraper:2");
							$a=json_decode($awards,true);
						
							foreach($a as $b)
							{
								if($b['award_type'] == 0 || $b['award_type'] == 1)
								{
									$glob++;
								}
							}
						}
					}
				}
			
			}
		}
			
			//echo count($b_count)."<br><br>";
			$len = 9-count($b_count);
			for($ii=0;$ii<$len;$ii++)
			{
				$b_count[] = $glob;
			}
			
			$query = "INSERT INTO `bbqfirst_db`.`".$year."` (`team_num`,`wk0`,`wk1`,`wk2`,`wk3`,`wk4`,`wk5`,`wk6`,`wk7`,`cmp`) VALUES ('$t', '$b_count[0]', '$b_count[1]', '$b_count[2]', '$b_count[3]', '$b_count[4]', '$b_count[5]', '$b_count[6]', '$b_count[7]', '$b_count[8]')";
			$mysqli->query($query);
		
	}
	} else {

 header("Location: /");
 }
?>