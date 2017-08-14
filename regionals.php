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
<?php include "navheader.html"; ?>	

	<script>
	function subform(){
		document.getElementById("yrs").submit();
	}
	</script>

<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
	if(date("n") < 11)
	{
		$year = date("Y");
	}
	else
	{
		$year = date("Y") + 1;
	}
	
	$sw = (int)$_GET['sw'];
	
	if($_GET['year'])
	{
		$year = $_GET['year'];
	}
	
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/". $year ."?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$regional=json_decode($string,true);
	usort($regional,function($a,$b) {return strnatcasecmp($a['name'],$b['name']);});
?>
	
<div><h1>FRC Event List 
<form method="get" id="yrs">
<select name="year" onchange="subform()">
	<?php
		for($y = $max_year; $y >= 2005; $y--)
		{
			$option_txt = "<option value=" . $y;
			if($year == $y)
			{
				$option_txt .= ' selected="selected"';
			}
			else
			{
				$option_txt .= " ";
			}
			
			$option_txt .= ">" . $y . "</option>";
			echo  $option_txt;
		}
	?>
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