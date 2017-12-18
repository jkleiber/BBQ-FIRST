<?php
ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$clear = $mysqli->query("TRUNCATE TABLE `bbqfirst_db`.`2014`");
	
	$tim=2014;
	$t=624;
	$glob=0;
		
		if(!$clear)
		{
			die('Error: ' . mysqli_error($mysqli)); 
		}
		
			$qq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_info.sponsored, regional_info.reg_key, awards.team_num, awards.award_id
			FROM awards
			INNER JOIN regional_info ON awards.reg_key = regional_info.reg_key
			WHERE awards.team_num = '$t'
			AND regional_info.year < '$tim'";
			$event = $mysqli->query($qq);
			
			while($wz = mysqli_fetch_array($event))
			{
				$glob++;
			}
			$b_count[] = $glob;
			
			$qqq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_info.sponsored, regional_info.reg_key, awards.team_num, awards.award_id,
			COUNT( * ) 
			FROM awards
			INNER JOIN regional_info
			WHERE awards.reg_key = regional_info.reg_key
			AND awards.team_num =  '$t'
			AND regional_info.year =  '$tim'
			GROUP BY awards.reg_key";
			$eventd = $mysqli->query($qqq);
			
			while($row = mysqli_fetch_array($eventd))
			{
				$wk=$row['yearweek'];
				
				if($wk=="cmp")
				{
					$wk=8;
				}
				
				$inwk=$row['COUNT( * )'];
				$lim=$wk-count($b_count);
				for($ii=0;$ii<$lim;$ii++)
				{
					$b_count[] = $glob;
				}
				$glob+=$inwk;
			}
			
			$len = 9-count($b_count);
			for($ii=0;$ii<$len;$ii++)
			{
				$b_count[] = $glob;
			}
			
			$sql = "INSERT INTO `bbqfirst_db`.`".$tim."` (`team_num`,`wk0`,`wk1`,`wk2`,`wk3`,`wk4`,`wk5`,`wk6`,`wk7`,`cmp`) VALUES ('$t', '$b_count[0]', '$b_count[1]', '$b_count[2]', '$b_count[3]', '$b_count[4]', '$b_count[5]', '$b_count[6]', '$b_count[7]', '$b_count[8]')";
			$mysqli->query($sql) or trigger_error($mysqli->error."[$sql]");
			
?> 
		