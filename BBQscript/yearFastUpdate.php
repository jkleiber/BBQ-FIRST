<?php
session_start();
	if($_SESSION['code']=="bbqfirstadmin")
	{
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	$limit = 0;
	
	for($i=12;;$i++)
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
	
	//Set this to change the year
	$tt=2017;
	
	$clear = $mysqli->query("TRUNCATE TABLE `bbqfrcx1_db`.`".$tt."`");
		
	if(!$clear)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}
	
	for($t=1;$t<=$limit;$t++)
	{
		//echo $t . "<br>";
		//$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
		//$time=json_decode($part,true);
		
		$b_count = array();
		$lastwk = 0;
		$bbb = 0; 
		$wk=0;
		$bb = 0; //during weeks
		$glob = 0; //before specified year
		
		//This is a sketchy way to make sure the year flag at the top works
		$tim=$tt;
		
			$b_count = array();
			$glob = 0;
			$qq = "SELECT * FROM `".($tim-1)."` WHERE team_num = '$t'";
			$teamq = $mysqli->query($qq);
			$r = mysqli_fetch_assoc($teamq);
			$glob+=$r['cmp'];
			$b_count[] = $glob;
			
$qqq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_info.sponsored, regional_info.reg_key, awards.team_num, awards.award_id,
COUNT( * ) 
FROM awards
INNER JOIN regional_info
WHERE awards.reg_key = regional_info.reg_key
AND awards.team_num =  '$t'
AND regional_info.year =  '$tim'
GROUP BY awards.reg_key
ORDER BY regional_info.yearweek ASC";
			$eventd = $mysqli->query($qqq);
			
			while($row = mysqli_fetch_array($eventd))
			{
				$wk=$row['yearweek'];
				
				if($wk=="cmp")
				{
					$wk=9;
				}
				
				$inwk=$row['COUNT( * )'];
				$lim=$wk-count($b_count);
				for($ii=0;$ii<$lim;$ii++)
				{
					$b_count[] = $glob;
				}
				$glob+=$inwk;
			}
			
			$len = 10-count($b_count);
			for($ii=0;$ii<$len;$ii++)
			{
				$b_count[] = $glob;
			}
			
			$sql = "INSERT INTO `bbqfrcx1_db`.`".$tim."` (`team_num`,`wk0`,`wk1`,`wk2`,`wk3`,`wk4`,`wk5`,`wk6`,`wk7`,`wk8`,`cmp`) VALUES ('$t', '$b_count[0]', '$b_count[1]', '$b_count[2]', '$b_count[3]', '$b_count[4]', '$b_count[5]', '$b_count[6]', '$b_count[7]', '$b_count[8]', '$b_count[9]')";
			$mysqli->query($sql) or trigger_error($mysqli->error."[$sql]");
			unset($b_count);
		
		
	}
}	
else
{
	header("Location: ../../logout.php");
}
?>