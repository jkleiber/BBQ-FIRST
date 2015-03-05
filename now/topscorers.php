<?php

error_reporting(E_ALL ^ E_NOTICE);
$key=$_GET['key'];
$key = strtoupper($key);

$currentHigh = 0;

$coopArr = array();
$autoArr = array();
$info = array();

@$html = file_get_contents('http://www2.usfirst.org/2014comp/events/'.$key.'/rankings.html');
if ( $html === false )
{
   $info[] = "1";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
}
else
{
$dom = new DOMDocument();
@$dom->loadHTML($html);


	$table = $dom->getElementsByTagName('table')->item(2);
	foreach($table->getElementsByTagName('tr') as $row)
	{
		$tds = $row->getElementsByTagName('td');
		
		$coop = $tds->item(3)->nodeValue;
		$auto = $tds->item(4)->nodeValue;
		if($coop > $currentcoop)
		{
			unset($coopArr);
			$coopArr = array();
			
			$currentcoop = $coop;
			$coopArr[] = $tds->item(1)->nodeValue;
			$coopArr[] = $tds->item(3)->nodeValue;
			
		}
		if($auto > $currentauto)
		{
			unset($autoArr);
			$autoArr = array();
			
			$currentauto = $auto;
			$autoArr[] = $tds->item(1)->nodeValue;
			$autoArr[] = $tds->item(4)->nodeValue;
			
		}
	}
}	
	$info[] = $coopArr;
	$info[] = $autoArr;
	echo json_encode($info);

?>