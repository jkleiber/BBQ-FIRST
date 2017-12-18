<?php
SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored
FROM regional_data
INNER JOIN regional_info
ON regional_data.reg_key=regional_info.reg_key;

SELECT team_info.nickname, team_info.years, team_info.rookie, `".$year."`.`cmp`, team_info.team_num, `".$year."`.`wk0`
FROM `".$year."`
INNER JOIN team_info
ON `".$year."`.team_num=team_info.team_num
WHERE team_info.`team_num`='$num'
?>