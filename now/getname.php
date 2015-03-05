<?php

	include('connect.php');
	
	$info = array();
	
	$num=$_GET['num'];
	
	$query = "SELECT * FROM `team_info` WHERE `team_num`='$num' LIMIT 1";
	$sql = $mysqli->query($query);
	$res = mysqli_fetch_assoc($sql);
	
	$info[] = $res['nickname'];
	
	echo json_encode($info);
?>