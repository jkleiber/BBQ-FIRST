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
	$bbq = 0;	
		$sum = 0;
		$num = 0;
		$i = 0;
		$yrs = 0;
	$mysqli->query("TRUNCATE TABLE `bbqfrcx1_db`.`special`");
	$sl = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`2015` ORDER BY `team_num` DESC");
	while($ow = mysqli_fetch_array($sl))
	{
		$top= $ow['team_num'];
		//for($val=1;$val<=$top;$val++)
		//{
			if($ow['nickname']!="" && $ow['team_num']!="" && $ow['rookie']!="")
			{
			$val=$ow['team_num'];
			$sql = $mysqli->query("SELECT team_info.team_num, team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				WHERE `2015`.`team_num`='$val'
				LIMIT 1");
			$row = mysqli_fetch_assoc($sql);
			$sum += $row["cmp"];
			$num++;
		
			if($row["rookie"] != 0)
			{
				$yrs += $row["years"];
			}
	
		$arr[] = $val;
		$i++;
			}
		
	}
	if($num != 0)
	{
		$bbq=$sum/$num;
	}
	
	if($yrs == 0)
	{
		$yrs = 1;
	}
	
	$bbqpdq = $bbq/$yrs;
	
	
	$dbq = "INSERT INTO `bbqfrcx1_db`.`special` (`name`, `banners`, `sauce`, `bbq`, `teams`,`bbqpdq`) VALUES ('alltems','$sum','0','$bbq','$num','$bbqpdq')";
	$mysqli->query($dbq);
	
	echo $sum;
	echo "<br>";
	echo $num;
	echo "<br>";
	echo $bbq;
	echo "<br>";
	echo $bbqpdq;
	echo "<br>";
	echo "<br>";
	
	$bbq = 0;	
	$sum = 0;
	$num = 0;
	$i = 0;
	$yrs = 0;
	
	//$sq = $mysqli->query("SELECT * FROM `bbqfirst_db`.`bbq` ORDER BY `banners` DESC LIMIT 10");
	$qq = "SELECT team_info.team_num, team_info.nickname, team_info.years, team_info.rookie, `2015`.cmp, `2005`.cmp
				FROM `2015`
				INNER JOIN team_info
				ON `2015`.team_num=team_info.team_num
				INNER JOIN `2005`
				ON `2015`.team_num=`2005`.team_num
				ORDER BY `2015`.cmp DESC
				LIMIT 10";
	$sq = $mysqli->query($qq);
	while($roww = mysqli_fetch_array($sq))
	{
		$sum += $roww["cmp"];
		$sauc +=
		$num++;
		$yrs+=$roww['years'];
	}
	$bbq=$sum/$num;
	$bbqpdq=$bbq/$yrs;
	$db = "INSERT INTO `bbqfrcx1_db`.`special` (`name`, `banners`, `sauce`, `bbq`, `teams`,`bbqpdq`) VALUES ('toptems','$sum','0','$bbq','10','$bbqpdq')";
	$mysqli->query($db);
	
	echo $sum;
	echo "<br>";
	echo $num;
	echo "<br>";
	echo $bbq;
	echo "<br>";
	echo $bbqpdq;
	echo "<br>";
	echo "<br>";
	
	}
	else
	{
		header("Location: ../../logout.php");
	}
?>