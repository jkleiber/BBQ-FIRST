<?php
error_reporting(E_ALL ^ E_NOTICE);
	$info = array();
	$code = $_GET['key'];
	$code = strtolower($code);
	$opr = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $code ."/stats?X-TBA-App-Id=justin_kleiber:team_scraper:1");
	$oprlist=json_decode($opr,true);
	
	foreach($oprlist as $ol)
	{
		arsort($ol);
		$info[] = strval(array_keys($ol)[0]);
		$info[] = strval(array_values($ol)[0]);
	}
	
	echo json_encode($info);
?>