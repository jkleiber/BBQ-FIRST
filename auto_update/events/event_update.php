<?php
	//Event Updater
	include("../connect.php");
	include("build_events.php");
	
	$display = "UPDATE `bbqfrcx1_db`.`maintenance` SET `message`='Automatically Updating Events...' WHERE `flag` = 'ndisplay'";
	$mysqli->query($display);
	
	$number_to_update = 5;
	
	//get the events we want to update
	$contents = file_get_contents("./event_keys.txt");
	$file = fopen("event_keys.txt","r+");
	$year = date("Y");
	
	$keys = "";
	
	$num_lines=-1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
		$num_lines++;
	} 
	
	if($num_lines < $number_to_update)
	{
		$number_to_update = $num_lines;
	}
	
	$i=0;
	while($i<$number_to_update && !feof($file))
	{
		/*
		$date = new DateTime();
		$start = $date->getTimestamp();
		*/
		$key = fgets($file);
		
		//get rid of all the special formatting
		$key = str_replace(" ","",$key);
		$key = str_replace("\r","",$key);
		$key = str_replace("\n","",$key);
	
		build_event($key, $year, $mysqli);
		
		$keys .= $key;
		$keys .= "_";
		
		$i++;
		/*
		$date2 = new DateTime();
		$end = $date2->getTimestamp();
		
		echo "<br>";
		$elapsed = $end - $start;
		echo "Event: ". $i ." Seconds: " . $elapsed;
		*/
	}
	
	fclose($file);
	/*
	echo "<br>";
	echo "Updated:" . $keys;
	*/
	//echo $keys;
	$contents = str_replace("\n","_",$contents);
	$contents = str_replace($keys,"",$contents);
	$contents = str_replace("_","\n",$contents);
	//echo "<br>";
	//echo strlen($keys);
	//echo "<br>";
	//echo $contents;
	file_put_contents("./event_keys.txt", $contents);
?>