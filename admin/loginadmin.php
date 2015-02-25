<?php

	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli('localhost','bbqfrcx1_bbquser','bbqpass', "bbqfrcx1_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	if($_POST)
	{
		if(isset($_POST['user']) && isset($_POST['pass']))
		{
			if($_POST['user']=='bbqadmin' && $_POST['pass']=='bbqadminpass')
			{
				session_start();
				$_SESSION['user']='bbqadmin';
				session_write_close();
				header("Location: adpanel.php");
			}
			else
			{
				echo "Invalclass user info!!";
			}
		}
	}

?>