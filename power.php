<?php
session_start();
if($_SESSION['power']=='yes')
{
?>
<html>
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Manual BBQ</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>

<script>
var i=0
function compare()
{
	if(document.getElementById('swallow').value == "African or European?" && i<1)
	{
	
		var h = document.createElement('h1');
		h.innerHTML="I don't know that!";
		h.setAttribute('id','dontknow_h');
		document.getElementById('ans').appendChild(h);
		
		var b = document.createElement('br');
		b.setAttribute('id','dontknow_b');
		document.getElementById('ans').appendChild(b);
		
		var a = document.createElement('a');
		a.setAttribute('href','/secretpower');
		a.setAttribute('id','dontknow_a');
		a.innerHTML="Unleash the power";
		i++;
		document.getElementById('ans').appendChild(a);
	}
	
}
</script>

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
	Enter the velocity of an unburdened swallow: <input id="swallow" type="text" onkeyup="compare()"/><br>
	
	<span id="ans">
		
	</span>
</div>
</body>
<?php } ?>