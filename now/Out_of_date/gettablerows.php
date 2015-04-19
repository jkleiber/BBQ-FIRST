<?php
$key=$_GET['key'];
$key = strtoupper($key);
$counter = array();

@$html = file_get_contents('http://www2.usfirst.org/2015comp/events/'.$key.'/matchresults.html');
if ( $html === false )
{
	$counter[] = "5";
	$counter[] = "5";
	$counter[] = "5";
}
else
{
$dom = new DOMDocument();
@$dom->loadHTML($html);

$qcount=0;
$ecount=0;
$scount=0;


$qtable = $dom->getElementsByTagName('table')->item(2);
foreach($qtable->getElementsByTagName('tr') as $row)
{
	$qcount++;
}
$etable = $dom->getElementsByTagName('table')->item(3);
foreach($etable->getElementsByTagName('tr') as $row)
{
	$ecount++;
}

$ahtml = file_get_contents('http://www2.usfirst.org/2014comp/events/'.$key.'/rankings.html');
$adom = new DOMDocument();
@$adom->loadHTML($ahtml);


$stable = $adom->getElementsByTagName('table')->item(2);
foreach($stable->getElementsByTagName('tr') as $row)
{
	$scount++;
}

$counter[] = $qcount;
$counter[] = $ecount;
$counter[] = $scount;
}
echo json_encode($counter);
?>