<?php


?>

<html>

<head profile="http://www.w3.org/2005/10/profile">
	<title>BBQ FIRST - Contact</title>
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
	<link rel="stylesheet" href="styler.css">
	<link rel="stylesheet" href="mobile_styler.css">
	<link rel="stylesheet" href="small_styler.css">
</head>
<body>
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	<br>
	<div class="the" style="padding:10px;">
	<form name="emailform" method="post" action="send_mail.php">
	<table class="contact">
		<tr><td>First Name</td><td><input type="text" name="first_name" required/></td></tr>
		<tr><td>Last Name</td><td><input type="text" name="last_name" required/></td></tr>
		<!-- <tr><td>Subject</td><td><input type="text" name="subject" required/></td></tr> -->
		<tr><td>Email Address</td><td><input type="text" name="address" required/><td></tr>
		<tr><td>Message</td><td><textarea rows="8" cols="50" name="message" required></textarea></td></tr>
		<tr><td><input class="sub" type="submit"/></td><td><td></tr>
	</table>
	</form>
	</div>
	</div>
	<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>