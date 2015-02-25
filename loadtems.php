<?php
	$mysqli = new mysqli('localhost','bbqfrcx1_bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	
	//load file
	$allowedExts = array("txt");
	$temp = explode(".", $_FILES["file"]["name"]);
	reset($temp);
	$name = current($temp);
	$extension = end($temp);
		
	if (in_array($extension, $allowedExts))
	{
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	} 
	else 
	{
		if (file_exists("customs/" . $_FILES["file"]["name"])) 
		{
			$iv = 1;
			while(file_exists("customs/" . $name . "(".$iv.").txt"))
			{
				$iv++;
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], "customs/" . $name . "(".$iv.").txt");
			$file = "customs/" . $name. "(".$iv.").txt";
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"], "customs/" . $_FILES["file"]["name"]);
			$file = "customs/" . $_FILES["file"]["name"];
		}
	
	
	$teams = file_get_contents($file);
	$list = explode("\n", $teams);
	
	$url = "manualbbq.php?";
	$it = 0;
	foreach($list as $l)
	{
		$_t = htmlspecialchars($mysqli->real_escape_string($l));
		$_t = preg_replace("/[^0-9]/", "", $_t);
		$url.="teams".$it."=".$_t."&";
		$it++;
	}
		$url = rtrim($url, "&");
		header("Location:".$url);
	}
	
	}
	else
	{
		echo "Invalid File Format";
	}
?>
