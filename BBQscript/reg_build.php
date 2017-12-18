<?php
	session_start();
	error_reporting(E_ALL ^ E_NOTICE);
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
		$cq = "TRUNCATE TABLE `bbqfrcx1_db`.`regional_data`";
		$clear = $mysqli->query($cq) or trigger_error($mysqli->error."[$cq]");
		
		if(!$clear)
		{
			die('Error: ' . mysqli_error($mysqli)); 
		}
		
		for($i=2005;$i<=2017;$i++)
		{
			set_time_limit(0);
			$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$i."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
			$regional=json_decode($string,true);
			usort($regional,function($a,$b) {return strnatcasecmp($a[name],$b[name]);});
	
			foreach($regional as $r)
			{
				
				$key = $r['key'];
				$flag = $i . "cmp";
				
				if($key!="2014cmp" && $key!="2015cmp")
				{
				$q = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$key'";
				$sql = $mysqli->query($q)  or trigger_error($mysqli->error."[$q]");
				$row = mysqli_fetch_assoc($sql);
				
				$week = $row['yearweek'];
				$year = $row['year'];
				$o = $row['sponsored'];
				
				$tems=0;
				$bb=0;
				$sce=0;
				$brick=0;
				$ri=0;
				$years=0;
				
				$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$key."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
				$teamlist=json_decode($teams,true);
				usort($teamlist,function($a,$b) {return strnatcasecmp($a[team_number],$b[team_number]);});
		
				foreach($teamlist as $t)
				{
					$n = $t['team_number'];
					if($week!="cmp")
					{
						$wktag = "wk" . ($week-1);
					}
					else
					{
						if($o="official")
						{
							if($i>=2016)
							{
								$wktag = "wk8";
							}
							else
							{
								$wktag = "wk7";
							}
						}
						else
						{
							$wktag = "cmp";
						}
					}
					
					$ti = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$year."` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
					$tro = mysqli_fetch_assoc($ti);
					$bb+=$tro[$wktag];
					
					$tif = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`2005` WHERE `team_num` = '$n' LIMIT 1") or trigger_error($mysqli->error);
					$trro = mysqli_fetch_assoc($tif);
					$sce+=($tro[$wktag]-$trro['wk0']);
					
					if(($year-4)>=2005)
					{
						$range = ($year-4);
						$tid = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$range."` WHERE `team_num` = '$n' LIMIT 1") or trigger_error($mysqli->error);
						$trk = mysqli_fetch_assoc($tid);
						$brick+=$tro[$wktag]-$trk[$wktag];
					}
					else
					{
						$brick=0;
					}
					
					if(($year-1)>=2005)
					{
					$lastyr=($year-1);
					$tir = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$lastyr."` WHERE `team_num` = '$n' LIMIT 1") or trigger_error($mysqli->error);
					$trkr = mysqli_fetch_assoc($tir);
					$ri+=$tro[$wktag]-$trkr[$wktag];
					}
					else
					{
						$ri=0;
					}
					
					$tird = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`team_info` WHERE `team_num` = '$n' LIMIT 1") or trigger_error($mysqli->error);
					$temm = mysqli_fetch_assoc($tird);
					$years+=$temm['years'];
					
					$tems++;
				}
				
				$bbq=0;
				$sauce=0;
				$briquette=0;
				$ribs=0;
				$bbqpdq=0;
				
				if($tems!=0)
				{
					$bbq = $bb/$tems;
					$sauce = $sce/$tems;
					$briquette= $brick/$tems;
					$ribs = $ri/$tems;
					$bbqpdq=$bbq/$years;
				}
				else
				{
					$bbq=0;
					$sauce=0;
					$briquette=0;
					$ribs=0;
					$bbqpdq=0;
				}
				
				$query = "INSERT INTO `bbqfrcx1_db`.`regional_data` (`reg_key`, `teams`, `years`, `banners`, `bbq`, `bbq_pdq`, `sauce`, `ribs`, `briquette`) VALUES ('$key', '$tems', '$years', '$bb', '$bbq', '$bbqpdq', '$sauce', '$ribs', '$briquette')";
				$mysqli->query($query)  or trigger_error($mysqli->error."[$query]");
				
				
				}
				else
				{
				
				
				$q = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$flag'";
				$sql = $mysqli->query($q)  or trigger_error($mysqli->error."[$q]");
				$row = mysqli_fetch_assoc($sql);
				
				$week = $row['yearweek'];
				$year = $row['year'];
				$o = $row['sponsored'];
				
				$tems=0;
				$bb=0;
				$sce=0;
				$brick=0;
				$ri=0;
				$years=0;
				
				$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/".$flag."?X-TBA-App-Id=justin_kleiber:team_scraper:1");
				$teamlist=json_decode($teams,true);
				foreach($teamlist["alliances"] as $all) {
					foreach ($all['picks'] as $key => $value) {
					$value = str_replace("frc","", $value); 
					$n=$value;
					
					$wktag = "wk7";
					
					$ti = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$year."` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
					$tro = mysqli_fetch_assoc($ti);
					$bb+=$tro[$wktag];
					
					$tif = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`2005` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
					$trro = mysqli_fetch_assoc($tif);
					$sce+=$tro[$wktag]-$trro['wk0'];
					
					if(($year-4)>=2005)
					{
						$range = ($year-4);
						$tid = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$range."` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
						$trk = mysqli_fetch_assoc($tid);
						$brick+=$tro[$wktag]-$trk[$wktag];
					}
					else
					{
						$brick=0;
					}
					
					if(($year-1)>=2005)
					{
					$lastyr=($year-1);
					$tir = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`".$lastyr."` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
					$trkr = mysqli_fetch_assoc($tir);
					$ri+=$tro[$wktag]-$trkr[$wktag];
					}
					else
					{
						$ri=0;
					}
					
					$tird = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`team_info` WHERE `team_num` = '$n' LIMIT 1")or trigger_error($mysqli->error);
					$temm = mysqli_fetch_assoc($tird);
					$years+=$temm['years'];
					
					$tems++;
					}
				}
				$bbq=0;
				$sauce=0;
				$briquette=0;
				$ribs=0;
				$bbqpdq=0;
				
				if($tems!=0)
				{
					$bbq = $bb/$tems;
					$sauce = $sce/$tems;
					$briquette= $brick/$tems;
					$ribs = $ri/$tems;
					$bbqpdq=$bbq/$years;
				}
				else
				{
					$bbq=0;
					$sauce=0;
					$briquette=0;
					$ribs=0;
					$bbqpdq=0;
				}
				
				$query = "INSERT INTO `bbqfrcx1_db`.`regional_data` (`reg_key`, `teams`, `years`, `banners`, `bbq`, `bbq_pdq`, `sauce`, `ribs`, `briquette`) VALUES ('$flag', '$tems', '$years', '$bb', '$bbq', '$bbqpdq', '$sauce', '$ribs', '$briquette')";
				$mysqli->query($query)or trigger_error($mysqli->error."[$query]");
				}
			}
			
		}
	}
	else
	{
		header("Location: ../../logout.php");
	}
?>