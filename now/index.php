<?php
error_reporting(E_ALL ^ E_NOTICE);

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

$string = file_get_contents("http://www.thebluealliance.com/api/v2/events/2015?X-TBA-App-Id=justin_kleiber:event_scraper:1");
$regional=json_decode($string,true);
usort($regional,function($a,$b) {return strnatcasecmp($a['start_date'],$b['start_date']);});
?>

<html>
	
	
	<head profile="http://www.w3.org/2005/10/profile">
	<title>BBQ EventCast</title>
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
	<link rel="stylesheet" href="styler.css">
	</head>
	
	<body>
		<div id="container">
			<div class="nav">
				<a href="index.php" class="nav"></a>
				<div class="nav_txt">
					Week 2
				</div> 
			</div>
			
			<table class="mainpage">
				<?php 
					foreach($regional as $r)
					{
						$key = $r['key'];
						$query = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$key'";
						$result = $mysqli->query($query);
						$ro = mysqli_fetch_assoc($result);
						
						if($ro['yearweek']==2)//change week to week
						{
						?>
							<tr>
								<td>
									<div>
										<a href="<?echo 'event_view.php?key=' . $key;?>" class="fade"><?php echo $ro['reg_name'];?> </a>
									</div>
								</td>
							</tr>
						<?php
						}
					}
				?>
			</table>
		</div>
	</body>
<html>
<?php
}
?>