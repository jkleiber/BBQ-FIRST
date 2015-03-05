<?php
$key=$_GET['key'];
$key = strtoupper($key);

$i=$_GET['t'];
$info = array();

@$html = file_get_contents('http://www2.usfirst.org/2014comp/Events/'.$key.'/rankings.html');
if ( $html === false )
{
   $info[] = "1";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0";
   $info[] = "0-0-0";
   $info[] = "0";
   $info[] = "0";
}
else
{
$dom = new DOMDocument();
@$dom->loadHTML($html);


	$table = $dom->getElementsByTagName('table')->item(2);
	$row=$table->getElementsByTagName('tr')->item($i);
	$tds = $row->getElementsByTagName('td');
	
	for($ii=0;$ii<=9;$ii++)
	{
		$info[] = $tds->item($ii)->nodeValue;
	}
}	
	echo json_encode($info);
?>