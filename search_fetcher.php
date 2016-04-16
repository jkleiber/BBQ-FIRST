<?php
function searchForStuff($mysqli,$text)
{
	//autocomplete code
	//$text = '%' . $_GET['keyword'] . '%';
	//$text = $_GET['keyword'];
	
	$text = $mysqli->real_escape_string($text);
	
	//echo $text;
	
	$team_query = "SELECT * FROM `team_info` WHERE (`team_num` LIKE '%" . $text . "%') OR (`nickname` LIKE '%".$text."%') ORDER BY `team_num` ASC";
	//$team_query = "SELECT * FROM `team_info` WHERE `nickname` LIKE '" . $text . "' ORDER BY `nickname` ASC";
	$event_query = "SELECT * FROM `regional_info` WHERE `reg_name` LIKE '%" . $text . "%' ORDER BY `reg_name` ASC";
	
	$team_result = $mysqli->query($team_query) or trigger_error;
	//$team_name_result = $mysqli->query($team_name_query) or trigger_error;
	$event_result = $mysqli->query($event_query) or trigger_error;

	
	$rows = [];
	if($team_result->num_rows > 0)
	{
		while($row = $team_result->fetch_array(MYSQLI_ASSOC))
		{
			$newRow = [];
			if($row['nickname'] != null && $row['nickname']!="")
			{
				$newRow["display"] = $row['team_num'] . " - " . $row['nickname'];
				$newRow["link"] = "./team_info.php?tem=" . $row['team_num'];
				
				$rows[] = $newRow;
			}
			//echo $row;
		}
	}
	if($event_result->num_rows > 0)
	{
		while($row = $event_result->fetch_array(MYSQLI_ASSOC))
		{
			$newRow = [];
			$newRow['display'] = $row['reg_name'];
			$newRow["link"] = "./event_info.php?key=" . $row['reg_key'] . "&year=" . $row['year'];
			//echo $row;
			
			$rows[] = $newRow;
		}
	}
	
	return json_encode($rows);
	//return json_encode($rows);
}
?>