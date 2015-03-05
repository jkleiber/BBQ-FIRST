<?php
$html = file_get_contents('http://www2.usfirst.org/2014comp/events/TXHO/matchresults.html');
$dom = new DOMDocument();
@$dom->loadHTML($html);

	$table = $dom->getElementsByTagName('table')->item(2);
	$i=0;
	foreach($table->getElementsByTagName('tr') as $row) {
		$tds = $row->getElementsByTagName('td');
		if($i>2){
		?>
		
		<div class="match">
		<?php
		echo $tds->item(1)->nodeValue . " ";
		?>
		</div>
		<div class="red">
		<?php
		echo $tds->item(2)->nodeValue . " ";
		echo $tds->item(3)->nodeValue. " ";
		echo $tds->item(4)->nodeValue . "";
		?>
		</div>
		<div class="winner">
		<?php
		echo $tds->item(8)->nodeValue . " ";
		?>
		</div>
		<div class="loser">
		<?php
		echo $tds->item(9)->nodeValue . " ";
		?>
		</div>
		<div class="blue">
		<?php
		echo $tds->item(5)->nodeValue . " ";
		echo $tds->item(6)->nodeValue. " ";
		echo $tds->item(7)->nodeValue . "";
		?>
		</div>
		
		
		<?php
		//break;
		}
		$i++;
	}
?>