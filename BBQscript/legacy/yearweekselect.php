<?php
session_start();
		if($_SESSION['code']=="bbqfirstadmin")
		{
		?>
<html>

<form action="downloadyear.php" method="post">
	Year<input name="year" type="number" min="2005" max="2015" required/>
	Week<input name="week" type="number" min="0" max="8" required/>
	<input type="submit"/>
</form>
<br>
Week 8 = CMP
</html>
<?php
} else {

 header("Location: index.html");
 }
?>