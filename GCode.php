<html>

<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<title>GCode Trickery</title>
<script src="dynamicform.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>

<script>
function subform(){
	document.getElementById("fileform").submit();
}
</script>

<div id="container">
	<div class="nav">
			<h1 style="text-align:left; padding:15px;margin:0">G Code Converter</h1>
	</div>
	<br>
	<br><br><br>
<div>
<h2>Input G Code Here please</h2>
<form action="GCodeConverter.php" method="post" id="fileform" enctype="multipart/form-data">
G Code: <input type="file" onchange="subform()" name="file"/><br><br>
<input type="submit" class="sub" value="Click this if it doesn't submit for some reason"></input>
</form>

</body>
</html>