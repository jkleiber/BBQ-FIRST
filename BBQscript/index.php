<?php
session_start();
if($_SESSION['code']==624)
{
	$mode=0;
	echo "Welcome Guest!";
}
else if($_SESSION['code']=="bbqfirstadmin")
{
	$mode=1;
	echo "Welcome Admin!";
}
else
{
	$mode=2;
}
?>
<html>

<head>
<title>BBQ Script Center</title>
<link rel="stylesheet" href="../../styler.css">
</head>
<body>
<a href="../../logout.php" style="float:right;margin-left:5px">Logout</a>
<a href="../" style="float:right;">Back</a>
<br><br>
General Actions:
	<table>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="row_getter.html" class="green">Show Progress</a><?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="copy_rename_tables.php" class="green">Export Tables</a><?php } ?>
			</td> 
		</tr>
	</table>
	<br>
	Every Now and then during the offseason:
	<table>
	<tr>
			<td>
				<?php if($mode!=2){?><a href="reg_info.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Load Regional Information</a><?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="reg_week.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Load Regional Weeks</a><?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="tem_info.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Load Team Information</a><?php } ?>
			</td>
		</tr>
	</table>
	<br>
	For the Current Season:
	<table>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="getawards.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Get Awards</a><?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="yearFastUpdate.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Build Team Data</a><?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php if($mode!=2){?><a href="reg_build.php" class="<?php if($mode==1){echo "green";}else{echo "notgreen";}?>">Build Regionals</a><?php } ?>
			</td>
		</tr>
	</table>
</body>
</html>