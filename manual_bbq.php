<?php
include('connect.php');
	
$query="SELECT * FROM `maintenance` LIMIT 1";
$sqli=$mysqli->query($query);

$row=mysqli_fetch_assoc($sqli);
$fleg=$row['flag'];
if($fleg=="minutes" || $fleg=="hours")
{
include 'downtime.php';
}
else
{
?>

<html>

<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<title>BBQ FIRST - Calculator</title>
<script src="dynamicform.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45234838-2', 'auto');
  ga('send', 'pageview');

</script>
<script>
function subform(){
	document.getElementById("fileform").submit();
}
</script>
<body onload="addTeamBox()">
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
<div>
<h2>Input team numbers or Batch Upload from Text File</h2>
<form action="loadtems.php" method="post" id="fileform" enctype="multipart/form-data">
Teams List (Text File, Newline Delimited): <input type="file" onchange="subform()" name="file"/><br>
</form>
<h4>Add teams quicker by pressing tab</h4>


<a href="#" onclick="addTeamBox()" id="addButton" >Add</a><br><br>
<form action="manualbbq.php" method="get">
<span id="myForm">
</span>

<input type="submit" class="sub" value="Submit"></input>
</form>
</div>
</div>
		<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>
<?php
}
?>