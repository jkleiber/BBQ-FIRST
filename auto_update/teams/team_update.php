<?php
	$date = new DateTime();
	$start = $date->getTimestamp();
	
	include("../connect.php");
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Automatically Updating Team Data...' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
	
	$number_to_update = 100;
	
	$contents = file_get_contents("./award_teams.txt");
	$file = fopen("award_teams.txt","r+");
	$year = date("Y");
	
	$i=0;
	
	$num_lines=-1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
		$num_lines++;
	} 
	
	if($num_lines < $number_to_update)
	{
		$number_to_update = $num_lines;
	}
	
	while($i<$number_to_update && !feof($file))
	{
		$t = fgets($file);
		
		//get rid of all the special formatting
		$t = str_replace(" ","",$t);
		$t = str_replace("\r","",$t);
		$t = str_replace("\n","",$t);
		
		$keys .= $t . "_";
		
		$b_count = array();
		$lastwk = 0;
		$bbb = 0; 
		$wk=0;
		$bb = 0; //during weeks
		$glob = 0; //before specified year
		
		//Get data from last season
		$b_count = array();
		$glob = 0;
		$qq = "SELECT * FROM `".($year-1)."` WHERE team_num = '$t'";
		$teamq = $mysqli->query($qq);
		$r = $teamq->fetch_assoc();
		$glob+=$r['cmp'];
		$b_count[] = $glob;
		
		//query database to find all banner data for the team
		$qqq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_info.sponsored, regional_info.reg_key, awards.team_num, awards.award_id,
				COUNT( * ) 
				FROM awards
				INNER JOIN regional_info
				WHERE awards.reg_key = regional_info.reg_key
				AND awards.team_num =  '$t'
				AND regional_info.year =  '$year'
				GROUP BY awards.reg_key
				ORDER BY regional_info.yearweek ASC";
		$eventd = $mysqli->query($qqq);
		
		while($row = $eventd->fetch_array(MYSQLI_ASSOC))
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
		
		$sql = "UPDATE `bbqfrcx1_db`.`".$year."` SET `wk0`='$b_count[0]',`wk1`='$b_count[1]',`wk2`='$b_count[2]',`wk3`='$b_count[3]',`wk4`='$b_count[4]',`wk5`='$b_count[5]',`wk6`='$b_count[6]',`wk7`='$b_count[7]',`wk8`='$b_count[8]',`cmp`='$b_count[9]' WHERE `team_num`='$t'";
		$mysqli->query($sql) or trigger_error($mysqli->error."[$sql]");
		unset($b_count);
		
		$i++;
	}
	
	//echo $keys;
	
	$contents = str_replace("\n","_",$contents);
	$contents = str_replace($keys,"",$contents);
	$contents = str_replace("_","\n",$contents);
	file_put_contents("./award_teams.txt", $contents);
	
	$newDate = new DateTime();
	$elapsed = $newDate->getTimestamp() - $start;
	echo $elapsed;
	
?>