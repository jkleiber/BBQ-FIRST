<?php
$key=$_GET['key'];
$key = strtoupper($key);

$i=$_GET['i'];
$info = array();

@$html = file_get_contents('http://www2.usfirst.org/2015comp/events/'.$key.'/matchresults.html');
if ( $html === false )
{
   $info[] = "0:00 AM";
   $info[] = "Playoff";
   $info[] = "0";
   $info[] = "1";
   $info[] = "2";
   $info[] = "3";
   $info[] = "4";
   $info[] = "5";
   $info[] = "6";
   $info[] = "0";
   $info[] = "0";
   
}
else
{
$dom = new DOMDocument();
@$dom->loadHTML($html);


	$table = $dom->getElementsByTagName('table')->item(3);
	$row=$table->getElementsByTagName('tr')->item($i);
	$tds = $row->getElementsByTagName('td');
	
	for($ii=0;$ii<=10;$ii++)
	{
		$info[] = $tds->item($ii)->nodeValue;
	}
}	
	echo json_encode($info);
?>