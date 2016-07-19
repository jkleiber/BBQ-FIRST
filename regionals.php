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
<title>BBQ FIRST - Event List</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
	<br>
	<br><br><br>
	
	<script>
	function subform(){
		document.getElementById("yrs").submit();
	}
	</script>

<?php
	error_reporting(E_ALL ^ E_NOTICE);
	if($_GET['year'])
	{
	$sw = (int)$_GET['sw'];
	$year = $_GET['year'];
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/". $year ."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	}
	else
	{
	$sw = (int)$_GET['sw'];
	$year = 2015;
	//justin_kleiber:event_scraper:1
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/2014?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
	}
?>
	
<div><h1>FRC Event List 
<form method="get" id="yrs">
<select name="year" onchange="subform()">
	<option value="2016" <?php if($year == 2016){echo 'selected="selected"';}else{echo "";}?>>2016</option>
	<option value="2015" <?php if($year == 2015){echo 'selected="selected"';}else{echo "";}?>>2015</option>
	<option value="2014" <?php if($year == 2014){echo 'selected="selected"';}else{echo "";}?>>2014</option>
	<option value="2013" <?php if($year == 2013){echo 'selected="selected"';}else{echo "";}?>>2013</option>
	<option value="2012" <?php if($year == 2012){echo 'selected="selected"';}else{echo "";}?>>2012</option>
	<option value="2011" <?php if($year == 2011){echo 'selected="selected"';}else{echo "";}?>>2011</option>
	<option value="2010" <?php if($year == 2010){echo 'selected="selected"';}else{echo "";}?>>2010</option>
	<option value="2009" <?php if($year == 2009){echo 'selected="selected"';}else{echo "";}?>>2009</option>
	<option value="2008" <?php if($year == 2008){echo 'selected="selected"';}else{echo "";}?>>2008</option>
	<option value="2007" <?php if($year == 2007){echo 'selected="selected"';}else{echo "";}?>>2007</option>
	<option value="2006" <?php if($year == 2006){echo 'selected="selected"';}else{echo "";}?>>2006</option>
	<option value="2005" <?php if($year == 2005){echo 'selected="selected"';}else{echo "";}?>>2005</option>
</select>
	<input type="hidden" name="sw" value="<?php echo $sw; ?>" />
<form>
</h1>
</div>



	<table class="mainpage">
<?php	
	foreach($regional as $r)
	{
	
	if($sw>=800 || $r['short_name']=="" || $r['short_name']==null)
	{
?>
<tr>
<td><div><a href="<?='event_info.php?key=' . $r['key']?>" class="fade"><?php echo $r['name'];?> </a></div></td>
</tr>
<?php }else{ ?>
<tr>
<td><div><a href="<?='event_info.php?key=' . $r['key']?>" class="fade"><?php echo $r['short_name'];?> </a></div></td>
</tr>
<?php }} ?>
</table>
 </div>
<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>

<?php
}
?>