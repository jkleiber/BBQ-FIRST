<?php
	$year = $_GET['year'];
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/". $year ."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	
	$regs = array();
	
	foreach($regional as $r)
	{
		$regs[] = $r['name'];
	}
	
	echo json_encode($regs);
?>