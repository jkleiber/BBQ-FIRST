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
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
if($_SESSION['user']=='bbqadmin')
{
include("connect.php");
?>
	<table>
		<table class="mainpage">
		<a href="adpanel.php" class="adm">Go Back</a>
			<tr><td><div class="the">File Access</div></td></tr>
			<tr>
				<td>
					<div>
						<a href="uploader.php" class="reg">Upload File(s)</a>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						<a href="filedownload.html" class="reg">Download File(s)</a>
					</div>
				</td>
			</tr>
					
		</table>
	</table>
<?php
}
else
{
	echo "Access Denied for non-admin";
}
?>
</body>
</html>