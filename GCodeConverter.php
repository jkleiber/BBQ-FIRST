<?php

	//load file
	$allowedExts = array("txt","gm");
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
		
		
			$lines = file_get_contents($file);
			$line = explode("\n", $lines);
			
			$codes = "";
			$count = count($line);
			$ii=1;
			foreach($line as $_t)
			{
				//echo $_t + "<br>";
				if(strpos($_t,"G00") !== false)
				{
					$codes.="M65\r\n";
					$codes.=$_t . "\r\n";
					$codes.="M64";
				}				
				else
				{
					$codes.=$_t;
				}
				
				if($ii!=$count)
				{
					$codes.="\r\n";
				}
				$ii++;
			}
			$newfile = "customs/" . $name .".gm";
			file_put_contents($newfile, $codes);
			
			
			header('Content-Type: application/gm');
			header("Content-Transfer-Encoding: Binary"); 
			header("Content-disposition: attachment; filename=\"" . basename($newfile) . "\""); 
			readfile($newfile);
			//header("Location: ".$newfile);
		}
	
	}
	else
	{
		echo "Invalid File Format";
	}
	
?>
