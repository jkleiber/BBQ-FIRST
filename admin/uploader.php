<?php
session_start();
if($_SESSION['user']=='bbqadmin')
{

	//error_reporting(E_ALL ^ E_NOTICE);
	include("connect.php");
	
$refresh = false;
//if($_POST)
//{
$f="Main";
	if(isset($_GET['fold']))
	{
		$f=$_GET['fold']."/";
		$redr=$_GET['fold'];
		$ff=ucfirst($_GET['fold']);
		
		$d = "../".$f;
		
		$webdir = scandir($d);
		$refresh = true;
	}
	else
	{
		$f="";
		$ff="Main";
		$redr="";
		$d = "../";
		$webdir = scandir($d);
		$refresh = true;
	}

?>

<html>
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon"
      type="image/png"
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="../styler.css">
<link rel="stylesheet" href="../mobile_styler.css">
<link rel="stylesheet" href="../small_styler.css">
<title>Admin File Manager</title>
</head>

<body>
<table>
		<table class="mainpage">
		<a href="admin_file_access.php" class="adm">Go Back</a>
			<tr><td><div class="the">File Manager</div></td></tr>
			
					
		</table>
	</table>
		
	<div class="the" style="padding:10px;">
		<form name="fileform" action="fileuploadscript.php" method="post" enctype="multipart/form-data">
			<!--Folder: <select name="fold_old">
						<option value="../">Main</option>
						<option value="../admin/">Admin</option>
					</select><br> -->
			<input name="fold" value="<?php echo $d;?>"/>
			<input name="fold_red" value="<?php echo $redr;?>"/>
			File: <input type="file" name="file"/><br><br>
			Auto-rename if exists?  <input type="checkbox" name="owrite"/><br><br>
			<!--<input type="submit" value="Submit"/> !-->
			<input type="submit" class="sub"></input>
		</form>
	</div>
	<br>
	<div class="the">
<?php
//function showfiles()
//{

/*}
else
{
	$d = "../";
	$webdir = scandir($d);
	$refresh = true;
} */
	//$di = "../admin/";
	//$addir = scandir($di);
?>

<?php	
//$del="nothing";
function reloadlist($webdir, $f, $ff)
{
	?>
	<br>
	<h3><?php echo $ff;?> Folder</h3>
	<table class="filebrowse">
	<th>Type</th>
	<th>Name</th>
	<th>Size</th>
	<th>Action</th>
	<?php 
	foreach($webdir as $web)
	{
	?>
	<tr class="filez">
	<td>
		<?php echo filetype("../". $f .$web);
		?>
		</td>
	<td>
		<?php 
		if($web == "." && filetype("../". $f .$web) == "dir")
		{
		echo "Up/Down One Level";
		}
		else if($web == ".." && filetype("../". $f .$web) == "dir")
		{
		echo "Up to Top of Directories";
		}
		else
		{
		echo $web;
		}
		
		?>
		</td>
	<td>
		<?php echo filesize("../". $f .$web);
		?>
		</td>
	<td>
		<?php 
		if(filetype("../". $f .$web) == "dir")
		{
			?>
				<a class="filer" href="?fold=<?php echo $web;?>">Open</a>
			<?php
		}
		else
		{
			//$del="../". $f .$web;
			?>
				<a class="filer" href="<?php echo "../". $f .$web; ?>">View</a>
			<?php
		} 
		?>
		</td>
		</tr>
	
		<?php 
	}
	?>
	</table>
	</div>
	<!--
<script>
	function deleteconfirm(file)
	{
		var x = file;
		if (confirm("Are you sure you want to delete "+ file +"?") == true) {
			<?php
				//unlink();
			?>
		}
	}
</script> -->
<?php
	$refresh=false;
}

if($refresh) reloadlist($webdir, $f, $ff);
?>

<br>
</body>

</html>


<?php

}
?>