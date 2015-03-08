<?php
	if($_POST)
	{
	if(isset($_POST['owrite']))
	{
		$ow = true;
	}
	else
	{
		$ow = false;
	}
	if(isset($_POST['fold']))
	{
		$fold = $_POST['fold'];
		//echo $fold . " #YOLO TEH CODEZ";
	}
	if(isset($_POST['fold_red']))
	{
		$redd = $_POST['fold_red'];
		//echo $fold . " #YOLO TEH CODEZ";
	}
	}
	//load file
	$disallowedExts = array("bin");
	$temp = explode(".", $_FILES["file"]["name"]);
	reset($temp);
	$name = current($temp);
	$extension = end($temp);
	
	
	
	if (!in_array($extension, $disallowedExts))
	{
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	} 
	else 
	{
		if (file_exists($fold . "/" . $_FILES["file"]["name"]) && $ow=="on") 
		{
			$iv = 1;
			while(file_exists($fold . $name . "(".$iv.").txt"))
			{
				$iv++;
			}
			move_uploaded_file($_FILES["file"]["tmp_name"], $fold . $name . "(".$iv.").".$extension);
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"], $fold . $name . "." .$extension);
		}
		
		header("Location: uploader.php?fold=".$redd);
		//include "uploadcomplete.php";
	}
	
	
	}
	else
	{
		echo "Invalclass File Format";
	}
?>