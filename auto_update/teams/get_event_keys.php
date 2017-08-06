<?php
	//Script that gets all event keys and lists them in a file
	include("../connect.php");	
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Fetching Official Events...' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
	
	$year = date("Y");
	
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/".$year."?X-TBA-App-Id=justin_kleiber:event_scraper:3");
	$regional = json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	
	$keys = "";
	
	foreach($regional as $r)
	{
		if($r['event_type'] < 5)
		{
			$keys .= $r['key'];
			$keys .= "\n";
		}
	}
	
	$file = "./event_keys.txt";
	file_put_contents($file, $keys);
?>