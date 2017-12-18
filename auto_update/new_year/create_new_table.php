<?php

include("../connect.php");

//Get current year and next year
$current_year = date("Y");
$next_year = $current_year + 1;

//Remove next year's table if it already exists
$remove_new_table_query = "DROP TABLE IF EXISTS `bbqfrcx1_db`.`$next_year`";
$mysqli->query($remove_new_table_query);

//Create the table
$create_table_query = "CREATE TABLE `bbqfrcx1_db`.`$next_year` SELECT * FROM `bbqfrcx1_db`.`$current_year`";
$mysqli->query($create_table_query);

for($i = 0; $i < 9; $i++)
{
	//Alter the columns to all equal previous year's cmp
	$week_update_query = "UPDATE `bbqfrcx1_db`.`$next_year` SET `wk$i`=`cmp` WHERE `team_num`=`team_num`";
	$mysqli->query($week_update_query) or trigger_error($mysqli->error."[$week_update_query]");;
}


?>