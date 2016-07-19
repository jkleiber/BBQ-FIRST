<?php
include('connect.php');
	
if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
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
	
	$bbq = 0;
	$code = $_GET['tem'];
	if(isset($_GET['year']))
	{
	$yer = $_GET['year'];
	}
	else
	{
		$yer = 2016;
	}
?>

<html>
<script>
var slideheights = [];
function load()
{
	var i;
	for(i=0;i<1;i++)
	{
		var d = document.getElementById("slide_"+i);
		var b = document.getElementById("slide_button_"+i);
		var l = document.getElementById("slide_li_"+i);
		slideheights[i] = d.clientHeight;
		//document.getElementById("p").innerHTML=d.clientHeight;
		//d.style.height="0px";
		b.innerHTML = "-";
		
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 0px;-moz-border-radius-bottomright: 0px;-webkit-border-bottom-left-radius: 0px;-moz-border-radius-bottomleft: 0px;margin-bottom:0px;");
		b.setAttribute("style","-webkit-border-bottom-left-radius: 0px;-moz-border-radius-bottomleft: 0px;");
	}

	
}
function expand(id)
{
	
	if(document.getElementById("slide_button_"+id).innerHTML!="+")
	{
		var d = document.getElementById("slide_"+id);
		d.style.height = '0px';
		
		var b = document.getElementById("slide_button_"+id);
		b.innerHTML = "+";
		
		var l = document.getElementById("slide_li_"+id);
	
		step(1,function(){ 
		if(d.style.height=="0px")
		{
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 5px;-moz-border-radius-bottomright: 5px;-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;margin-bottom:5px;"); 
		b.setAttribute("style","-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;");
		}
		});
	}
	else
	{
		var d = document.getElementById("slide_"+id);
		
		d.style.height=slideheights[id];
		
		var l = document.getElementById("slide_li_"+id);
		
		var b = document.getElementById("slide_button_"+id);
		b.innerHTML = "-";
		
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 0px;-moz-border-radius-bottomright: 0px;-webkit-border-bottom-left-radius: 0px;-moz-border-radius-bottomleft: 0px;margin-bottom:0px;");
		b.setAttribute("style","-webkit-border-bottom-left-radius: 0px;-moz-border-radius-bottomleft: 0px;");
		
		step(2,function(){ 
			d.style.opacity = '100'; 
		});
	}
}

function step(seconds, action)
{
	var counter = 0;
    var time = window.setInterval( function ()
    {
        counter++;
        if ( counter >= seconds )
        {
            action();
            window.clearInterval( time );
        }
    }, 1000 );
}

</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45234838-2', 'auto');
  ga('send', 'pageview');

</script>


<!--<p id="p"></p>!-->
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include('connect.php');
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	
	
	// /api/v2/event/2010sc/teams
	$string = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc". $code ."/".$yer."/events?X-TBA-App-Id=justin_kleiber:event_scraper:1");
	$n=json_decode($string,true);
	
	usort($n,function($a,$b) {return strnatcasecmp($a['start_date'],$b['start_date']);});
	$teams = file_get_contents("http://www.thebluealliance.com/api/v2/team/frc". $code ."?X-TBA-App-Id=justin_kleiber:team_scraper:1");
	$t=json_decode($teams,true);
	//usort($teamlist,function($a,$b) {return strnatcasecmp($a['team_number'],$b['team_number']);});
	
	//$tem = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`bbq` WHERE `team_num` = '$code' LIMIT 1");
	$tem = $mysqli->query("SELECT team_info.nickname, team_info.years, team_info.rookie, `".$yer."`.`cmp`, team_info.team_num, `".$yer."`.`wk0`, `".$yer."`.`wk1`, `".$yer."`.`wk2`, `".$yer."`.`wk3`, `".$yer."`.`wk4`, `".$yer."`.`wk5`, `".$yer."`.`wk6`, `".$yer."`.`wk7`
				FROM `".$yer."`
				INNER JOIN team_info
				ON `".$yer."`.team_num=team_info.team_num
				WHERE team_info.`team_num`='$code'");
	$ti = mysqli_fetch_assoc($tem);
	
	$temsau = $mysqli->query("SELECT team_info.nickname, team_info.years, team_info.rookie, `2005`.`cmp`, team_info.team_num, `2005`.`wk0`
				FROM `2005`
				INNER JOIN team_info
				ON `2005`.team_num=team_info.team_num
				WHERE team_info.`team_num`='$code'");
	$tisau = mysqli_fetch_assoc($temsau);
	
	$qqq = "SELECT * FROM awards
			WHERE awards.team_num =  '$code'
			AND awards.year <= '$yer'
			ORDER BY awards.year DESC";
			$eventas = $mysqli->query($qqq);
	
	//$sauce = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`sauce` WHERE `team_num` = '$code' LIMIT 1");
	//$sc = mysqli_fetch_assoc($sauce);
	include "navheader.html";
?>
<!--
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - <?php echo $t['nickname'];?> - <?php echo $yer;?></title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
</head>
!-->
<script>
var stats = [];
	
	function getYear(url)
	{
		var yearData = [];
		return $.getJSON(url).then(function(data){
			$.each(data, function (index, val) {
				console.log(val);
				yearData.push(val);
			});
			return yearData;
		});
	}
	
	$(document).ready(function() 
	{ 
		load();
	
		var context = document.getElementById("bbChart").getContext("2d");
		var data = {
			labels : ["2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015","2016"],
			datasets : [
				{
					label: "Highest BBQ Event",
				    fillColor: "rgba(0,0,255,.4)",
				    strokeColor: "#3A3AFF",
				    highlightFill: "#2121CE",
				    highlightStroke: "rgba(0,0,255,1)",
				    data: [0,0,0,0,0,0,0,0,0,0,0,0]
				}
			
			]
		};
		
		var bbChart = new Chart(context).Bar(data);
		var tNum = "<?php echo $code; ?>";
		getYear("getbbteam.php?t=" + tNum).then(function(returned){
			for(var i=0;i<12;i++)
			{
				bbChart.datasets[0].bars[i].value = returned[i];
				bbChart.update();
			}
		});
	});
</script>
	<style>
	.searcher
	{
	height:35px;
	width:85px;
	background-color:#1f1f1f;
	border-radius:5px;
	outline: 1px solid transparent;
	border: 1px solid transparent;
	color:#FFF;
	cursor: pointer;
	}
	.searcher:hover
	{
	background-color:#5f5f5f;
	}
	</style>
<script>
function linktoevent(key, yr)
{
	location.href = 'event_info.php?key=' + key;
}
function subform()
{
	document.getElementById("yrs").submit();
}
</script>
<body>
<!--
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
<br><br><br><br>  !-->
<title><?=$t['team_number'].": " .$t['nickname']?></title>
<div>
<?php
	if($t['website']!=null)
	{
?>
<h1><a class="standard_big" href="<?php echo $t['website'];?>"><?php echo "Team ".$t['team_number'].": " .$t['nickname']; ?></a></h1>
<?php
	} else {
?>
<h1><?php echo "Team ".$t['team_number'].": " .$t['nickname']; ?></h1>
<?php
}

$banners=$ti[$yer];
$tkf=$ti[$yer-1];
$tkt=$ti[$yer-2];

$diff = $ti["cmp"]-$ti["wk0"];

$saucy=$ti["cmp"]-$tisau["wk0"];
?>
</div>

<br>
<form method="get" id="yrs">
<input type="number" name="tem" class="short_input" value="<?php echo $code; ?>" placeholder="Team #"/>
<select name="year" onchange="subform()">
	<option value="2016" <?php if($yer == 2016){echo 'selected="selected"';}else{echo "";}?>>2016</option>
	<option value="2015" <?php if($yer == 2015){echo 'selected="selected"';}else{echo "";}?>>2015</option>
	<option value="2014" <?php if($yer == 2014){echo 'selected="selected"';}else{echo "";}?>>2014</option>
	<option value="2013" <?php if($yer == 2013){echo 'selected="selected"';}else{echo "";}?>>2013</option>
	<option value="2012" <?php if($yer == 2012){echo 'selected="selected"';}else{echo "";}?>>2012</option>
	<option value="2011" <?php if($yer == 2011){echo 'selected="selected"';}else{echo "";}?>>2011</option>
	<option value="2010" <?php if($yer == 2010){echo 'selected="selected"';}else{echo "";}?>>2010</option>
	<option value="2009" <?php if($yer == 2009){echo 'selected="selected"';}else{echo "";}?>>2009</option>
	<option value="2008" <?php if($yer == 2008){echo 'selected="selected"';}else{echo "";}?>>2008</option>
	<option value="2007" <?php if($yer == 2007){echo 'selected="selected"';}else{echo "";}?>>2007</option>
	<option value="2006" <?php if($yer == 2006){echo 'selected="selected"';}else{echo "";}?>>2006</option>
	<option value="2005" <?php if($yer == 2005){echo 'selected="selected"';}else{echo "";}?>>2005</option>
</select><br>
<input type="submit" value="Go!" class="searcher"/>
</form>
<h3>Stats from <?php echo $yer;?></h3>
<table class="stats">
<tr class="val">
	<td class="stat">Banners to Date</td>
	<td class="val"><?php echo $ti["cmp"]; ?></td>
</tr>
<tr class="val">
	<td class="stat">Banners Won in <?php echo $yer;?></td>
	<td class="val"><?php echo $diff ?></td>
</tr>

<tr class="val">
	<td class="stat">Banners Won since 2005</td>
	<td class="val"><?php echo $saucy; ?></td>
</tr>



</table>
<br>
<li  class="slidelis" id="slide_li_0">
		<span id="collapseView" style="font-size:18px;">
			<button class="slidebuttons" id="slide_button_0" onclick="expand('0')">-</button> A1 Awards (to date)
		</span>
	</li>
<div id="slide_0" class="slidedivs">
	<table>
<?php
while($rower = mysqli_fetch_array($eventas))
{
?>
<tr style="background-color:transparent;"><td>
<?php
	$ev = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $rower['reg_key'] ."?X-TBA-App-Id=justin_kleiber:team_scraper:1");
	$e=json_decode($ev,true);
	echo $rower['name'] . " at " . $e['name'] . " in " . $rower['year'];
?>
</td></tr>
<?php
}
?>
</table>
</div>
<br>
<h3>Events from <?php echo $yer;?></h3>
<table class="region">
<th class="region">Week</th>
<th class="region">Event Name</th>
<th class="region">BBQ</th>
<th class="region">SAUCE</th>
<?php	

	foreach($n as $event)
	{
	
	/*	THE OLD WAY
	//$text = file_get_contents('http://www.thebluealliance.com/team/'. $t[team_number] .'/history');
	//$win = substr_count($text, "Winner");
	//$chair = substr_count($text, "Regional Chairman");
	//$div_win = substr_count($text, "Division Champion");
	
	$bb=$win+$chair+$div_win;*/
	
	//THE NEW WAY
	$e = $event['key'];
	$qrq = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, regional_info.reg_key
FROM regional_data
INNER JOIN regional_info
ON regional_data.reg_key=regional_info.reg_key
WHERE regional_info.reg_key = '$e'";
$sql = $mysqli->query($qrq);
	//$sql = $mysqli->query("SELECT * FROM `bbqfrcx1_db`.`regional_data` WHERE `reg_key` = '$e' LIMIT 1");
	$r = mysqli_fetch_assoc($sql);
	
	$vars = array($r['reg_key'], $r['year']);
	$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
	$jsvars = str_replace("[","", $jsvars);
	$jsvars = str_replace("]","", $jsvars);
	
	$win=false;
	$owin=false;
	
	if($r['sponsored']=="official")
	{
		$dispwk = $r["yearweek"];
	}
	else
	{
		$dispwk = "offseason";
	}
	$lolkey = $r["reg_key"];
	$qv = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_info.sponsored, regional_info.reg_key, awards.team_num, awards.award_id, awards.name
			FROM awards
			INNER JOIN regional_info
			WHERE awards.reg_key = regional_info.reg_key
			AND awards.team_num =  '$code'
			AND regional_info.year = '$yer'
			AND awards.reg_key = '$lolkey'";
			$eventvs = $mysqli->query($qv);
	$ws=mysqli_fetch_assoc($eventvs);
	
	if($r['sponsored']=="official")
	{
		if($r["reg_key"] == $ws["reg_key"])
		{
			$win = true;
		}
		else
		{
			$win = false;
		}
	}
	else
	{
		if($r["reg_key"] == $ws["reg_key"])
		{
			$owin = true;
		}
		else
		{
			$owin = false;
		}
	}
	
	if($win)
	{
?>
	<tr class="regions_win" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $dispwk; ?></td>
		<td class="region"><?php echo $r["reg_name"]?></td>
		<td class="region"><?php echo $r["bbq"]?></td>
		<td class="region"><?php echo $r["sauce"];?></td>
	</tr>
<?php
}
	else if($owin)
	{
?>
	<tr class="off_win" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $dispwk; ?></td>
		<td class="region"><?php echo $r["reg_name"]?></td>
		<td class="region"><?php echo $r["bbq"]?></td>
		<td class="region"><?php echo $r["sauce"];?></td>
	</tr>
<?php
}else{
?>
	<tr class="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $dispwk; ?></td>
		<td class="region"><?php echo $r["reg_name"]?></td>
		<td class="region"><?php echo $r["bbq"]?></td>
		<td class="region"><?php echo $r["sauce"];?></td>
	</tr>
<?php
	}
}
?>
</table>

<br>

<h3>Blue Banners Acquired by Year</h3>

<canvas id="bbChart" width="800" height="400"></canvas>

 </div>
<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>

<?php
}
?>