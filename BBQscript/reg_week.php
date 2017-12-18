<?php
$WEEK_LIMIT = 7;

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
	
	for($i=2005;$i<=2017;$i++)
	{
		if($i>=2016)
		{
			$WEEK_LIMIT = 8;
		}
	set_time_limit(0);
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$i."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	
	
		$que = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `year`='$i' ORDER BY `week` ASC";
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
		
		echo "done.";
	}
} else {
 header("Location: ../../logout.php");
 }
?>