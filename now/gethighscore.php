<?php
error_reporting(E_ALL ^ E_NOTICE);
$key=$_GET['key'];
$key = strtoupper($key);

$currentHigh = 0;

@$html = file_get_contents('http://www2.usfirst.org/2014comp/events/'.$key.'/matchresults.html');
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
		
		$red = $tds->item(8)->nodeValue;
		$blue = $tds->item(9)->nodeValue;
		if($red > $currentHigh || $blue > $currentHigh)
		{
			unset($info);
			$info = array();
			
			if($red>=$blue)
			{
				$currentHigh = $red;
				$info[] = $tds->item(1)->nodeValue;
				
				$info[] = $tds->item(2)->nodeValue;
				$info[] = $tds->item(3)->nodeValue;
				$info[] = $tds->item(4)->nodeValue;
				
				$info[] = $tds->item(8)->nodeValue;
			}
			else
			{
				$currentHigh = $blue;
				$info[] = $tds->item(1)->nodeValue;
				
				$info[] = $tds->item(5)->nodeValue;
				$info[] = $tds->item(6)->nodeValue;
				$info[] = $tds->item(7)->nodeValue;
				
				$info[] = $tds->item(9)->nodeValue;
			}
			
			
		}
	}
}	
	echo json_encode($info);
?>