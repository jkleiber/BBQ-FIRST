<?php

include("../connect.php");

//Get current year
$current_year = date("Y");

//8 weeks in a competition year
$WEEK_LIMIT = 8;

$que = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `year`='$current_year' ORDER BY `week` ASC";
$regs = $mysqli->query($que);

$week = 1;
$firstrun=true;
$wkzero = 0;

while($r = $regs->fetch_array(MYSQLI_ASSOC))
{

	if($r['sponsored']=="official")
	{
		if(!$firstrun)
		{
			if($last!=$r['week'] && $week<$WEEK_LIMIT && $week!="cmp")
			{
				if($r['reg_key']!="2016scmb")
				{
					$last = $r['week'];
					$week++;
				}
			}
			else if($last!=$r['week'] && $week>=$WEEK_LIMIT)
			{
				$last = "cmp";
				$week="cmp";
			}
		}
		else
		{
			if($r['reg_key']!="2016scmb")
			{
				$last = $r['week'];
				$wkzero = $last;
				$firstrun=false;
			}
		}
	
		$key=$r['reg_key'];
		$query = "UPDATE `bbqfrcx1_db`.`regional_info` SET `yearweek`='$week' WHERE `reg_key`='$key'";
		$result = $mysqli->query($query);
	
	}
	else
	{
		if($r['week']>$wkzero)
		{
			$c = "cmp";
		}
		else
		{
			$c = "0";
		}
		$key=$r['reg_key'];
		$query = "UPDATE `bbqfrcx1_db`.`regional_info` SET `yearweek`='$c' WHERE `reg_key`='$key'";
		$result = $mysqli->query($query);
	}
	
	if(!$result)
	{
		die('Error: ' . mysqli_error($mysqli)); 
	}

}

?>