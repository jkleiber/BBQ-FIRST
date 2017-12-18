<?php

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	if($_POST)
	{
		$wk0 = $_POST['0'];
		$wk1 = $_POST['1'];
		$wk2 = $_POST['2'];
		$wk3 = $_POST['3'];
		$wk4 = $_POST['4'];
		$wk5 = $_POST['5'];
		$wk6 = $_POST['6'];
		$wk7 = $_POST['7'];
		$wkcmp = $_POST['cmp'];
		
		$tem = $_POST['team'];
		$year = $_POST['year'];
		
		$qq = "UPDATE `bbqfirst_db`.`".$year."` SET `wk0`='$wk0', `wk1`='$wk1', `wk2`='$wk2', `wk3`='$wk3', `wk4`='$wk4', `wk5`='$wk5', `wk6`='$wk6', `wk7`='$wk7', `cmp`='$wkcmp' WHERE `team_num`='$tem'";
		$mysqli->query($qq) or trigger_error($mysqli->error."['$qq']");
	}
?>

<html>
<form method="post">
<input type="number" name ="team" placeholder="team"/>
<input type="number" name ="year" placeholder="year"/>
<input type="number" name ="0" placeholder="0"/>
<input type="number" name ="1" placeholder="1"/>
<input type="number" name ="2" placeholder="2"/>
<input type="number" name ="3" placeholder="3"/>
<input type="number" name ="4" placeholder="4"/>
<input type="number" name ="5" placeholder="5"/>
<input type="number" name ="6" placeholder="6"/>
<input type="number" name ="7" placeholder="7"/>
<input type="number" name ="cmp" placeholder="cmp"/>
<input type="submit"/>
</form>
</html>