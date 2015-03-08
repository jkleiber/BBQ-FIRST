<?php

	include('connect.php');
	
	if($_POST)
	{
		if(isset($_POST['user']) && isset($_POST['pass']))
		{
			if($_POST['user']=='bbqadmin' && $_POST['pass']==$ini['adminpass'])
			{
				session_start();
				$_SESSION['user']='bbqadmin';
				session_write_close();
				header("Location: adpanel.php");
			}
			else
			{
				echo "Invalid user info!!";
			}
		}
	}

?>