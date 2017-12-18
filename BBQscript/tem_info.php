<?php
session_start();
		if($_SESSION['code']=="bbqfirstadmin")
		{

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
	
	
	$clear = $mysqli->query("TRUNCATE TABLE `bbqfrcx1_db`.`team_info`");
	
	if(!$clear)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}
    
	for($t=1;$t<=$limit;$t++)
	{
		$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
		$info=json_decode($string,true);
		
		$part = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc" . $t ."/years_participated?X-TBA-App-Id=justin_kleiber:team_info_scraper:1");
		$time=json_decode($part,true);
		$c = count($time);
		
		$name = $mysqli->real_escape_string($info['nickname']);
		$rook = $info["rookie_year"];
		
		$query = "INSERT INTO `bbqfrcx1_db`.`team_info` (`team_num`,`nickname`,`years`,`rookie`) VALUES ('$t','$name','$c','$rook')";
		$mysqli->query($query);
	}
} else {
 header("Location: ../../logout.php");
 }
?>