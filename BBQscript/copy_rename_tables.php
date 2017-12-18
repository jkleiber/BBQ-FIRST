<?php
session_start();
	if($_SESSION['code']=="bbqfirstadmin")
	{
	ini_set('max_execution_time', 0);
	set_time_limit(0);
	ignore_user_abort(true);

	$mysqli = new mysqli('localhost','bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	if($_GET)
	{
		if(isset($_GET['tbl']))
		{
			$old_t = $_GET['tbl'];
			
			$del = "DROP TABLE IF EXISTS bbqfrcx1_db.`".$old_t."_export`";
			$mysqli->query($del)or trigger_error($mysqli->error);
			
			$qq = "CREATE TABLE `bbqfrcx1_db`.`".$old_t."_export` LIKE `bbqfrcx1_db`.`".$old_t."`";
			$mysqli->query($qq)or trigger_error($mysqli->error);
			
			$qqq="INSERT `bbqfrcx1_db`.`".$old_t."_export` SELECT * FROM `bbqfrcx1_db`.`".$old_t."`;";
			$mysqli->query($qqq)or trigger_error($mysqli->error);
			
			header("Location: copy_rename_tables.php");
		}
	}
	
	$query = "select * from information_schema.tables where table_schema ='bbqfrcx1_db'";
	$tables = $mysqli->query($query);
?>
<h1> Exportable Tables </h1>
<?php
	while($row = mysqli_fetch_array($tables))
	{
		if (strpos($row['TABLE_NAME'],'export') === false) {
			echo '<a href="?tbl=' . $row['TABLE_NAME'] . '">' . $row['TABLE_NAME'] . '</a>';
			echo "<br>";
		}
	}
?>
<h1>Exported Tables</h1>
<?php
$tablez = $mysqli->query($query);
	while($row = mysqli_fetch_array($tablez))
	{
		if (strpos($row['TABLE_NAME'],'export') !== false) {
			echo '<a href="?tbl=' . $row['TABLE_NAME'] . '">' . $row['TABLE_NAME'] . '</a>';
			echo "<br>";
		}
	}
	}
?>