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
<title>BBQ FIRST - Event Rankings</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<body onload="load()">
<div id="container">
<?php include "navheader.html"; ?>
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	if($_GET['year'])
	{
		$year=$_GET['year'];
	}
	else
	{
		if(date("n") < 11)
		{
			$year = date("Y");
		}
		else
		{
			$year = date("Y") + 1;
		}
	}

	include('connect.php');
	
	if($year!=1)
	{
		$oquery = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE `year`='$year' AND `sponsored`='official' 
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC";
		$uquery = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE `year`='$year' AND `sponsored`='unofficial' 
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC";
		$que = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE `year`='$year'
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC
				LIMIT 25";
		//$oquery = "SELECT * FROM `bbqfrcx1_db`.`regional_data` WHERE `year`='$year' AND `sponsored`='official' ORDER BY `regional_data`.`bbq` DESC, `teams` DESC";
		//$uquery = "SELECT * FROM `bbqfrcx1_db`.`regional_data` WHERE `year`='$year' AND `sponsored`='unofficial' ORDER BY `regional_data`.`bbq` DESC, `teams` DESC";
		//$que = "SELECT * FROM `bbqfrcx1_db`.`regional_data` WHERE `year`='$year' ORDER BY `regional_data`.`bbq` DESC, `teams` DESC LIMIT 25";
		$top = $mysqli->query($que);
	}
	else
	{
		$oquery = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE `sponsored`='official' 
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC
				LIMIT 100";
		$uquery = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				WHERE `sponsored`='unofficial' 
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC
				LIMIT 100";
		$que = "SELECT regional_info.reg_name, regional_info.year, regional_info.yearweek, regional_data.bbq, regional_data.sauce, regional_data.ribs, regional_data.bbq_pdq, regional_data.briquette, regional_info.sponsored, `regional_data`.`teams`, regional_data.reg_key
				FROM regional_data
				INNER JOIN regional_info
				ON regional_data.reg_key=regional_info.reg_key
				ORDER BY `regional_data`.`ribs`+0 DESC, `regional_data`.`teams` DESC
				LIMIT 25";
		//$oquery = "SELECT * FROM `bbqfrcx1_db`.`regionaldata` WHERE `sponsored`='official' ORDER BY `regionaldata`.`sauce` DESC, `teams` DESC LIMIT 100";
		//$uquery = "SELECT * FROM `bbqfrcx1_db`.`regionaldata` WHERE `sponsored`='unofficial' ORDER BY `regionaldata`.`sauce` DESC, `teams` DESC LIMIT 100";
		//$que = "SELECT * FROM `bbqfrcx1_db`.`regionaldata` ORDER BY `regionaldata`.`sauce` DESC, `teams` DESC LIMIT 25";
		$top = $mysqli->query($que);
	}
	
	
?>
<script>
var dh;
var ddh;
var toh;
function load()
{
	var d = document.getElementById("officials");
	dh = d.clientHeight;
	d.style.height = dh;
	
	var dd = document.getElementById("unofficials");
	ddh = dd.clientHeight;
	dd.style.height = ddh;
	
	var to = document.getElementById("tops");
	toh = to.clientHeight;
	to.style.height = toh;
}
function expand(id)
{
	
	if(document.getElementById(id).innerHTML!="+")
	{
		var d = document.getElementById(id+"s");
		d.style.height = '0px';
		
		var b = document.getElementById(id);
		b.innerHTML = "+";
		
		var l = document.getElementById("li_"+id);
	
		step(.5,function(){ 
		if(d.style.height=="0px")
		{
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 5px;-moz-border-radius-bottomright: 5px;-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;margin-bottom:15px;"); 
		b.setAttribute("style","-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;");
		}
		});
	}
	else
	{
		var d = document.getElementById(id+"s");
		
		if((id+"s")=="officials")
		{
			d.style.height = dh;
		}
		if((id+"s")=="tops")
		{
			d.style.height = toh;
		}
		else
		{
			d.style.height = ddh;
		}
		
		var l = document.getElementById("li_"+id);
		
		var b = document.getElementById(id);
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

function linktoevent(key, yr)
{
	location.href = 'event_info.php?key=' + key;
}
function subform(){
		document.getElementById("yrs").submit();
	}
</script>
<br>
<form method="get" id="yrs">
<select name="year" onchange="subform()">
	<option value="1" <?php if($year == 1){echo 'selected="selected"';}else{echo "";}?>>All-Time</option>
	<option value="2017" <?php if($year == 2017){echo 'selected="selected"';}else{echo "";}?>>2017</option>
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
<form>
<ul>
<li id="li_top" class="li_top">
<span id="collapseView">

<button type="button" id="top" class="top" onclick="expand('top')">-</button>
<!-- 	<a class="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
Overall Top 25 Events
</span>
</li>

	<div id="tops" class="tops">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Regional Name</th>
	<th class="region">Total Teams</th>
	<th class="region">RIBS</th>
<?php
$rnk = 0;
	while($r = $top->fetch_array(MYSQLI_ASSOC))
	{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['ribs'];
			/*
			if($r["teams"]!=0)
			{
				$bbq = $r["banners"]/$r["teams"];
			}
			else
			{
				$bbq = 0;
			}
			*/
			$vars = array($r["reg_key"], $r['year']);
			$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
			$jsvars = str_replace("[","", $jsvars);
			$jsvars = str_replace("]","", $jsvars);
?>

	<tr class="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $rnk;?></td>
		<?php
		if($year!=1)
		{
		?>
			<td class="region"><?php echo $r["reg_name"];?></td>
		<?php
		} else {
		?>
			<td class="region"><?php echo $r["reg_name"]. " " . $r["year"];?></td>
		<?php
		}
		?>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
	}
?>
	</table>
	</div>
	
<li id="li_official" class="li_official">
<span id="collapseView">

<button type="button" id="official" class="official" onclick="expand('official')">-</button>
<!-- 	<a class="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
<?php
	if($year!=1)
	{
?>
Official Events
<?php
	} else {
?>
Top 100 Official Events
<?php
}
?>
</span>
</li>

	<div id="officials" class="officials">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Regional Name</th>
	<th class="region">Total Teams</th>
	<th class="region">RIBS</th>
<?php
$rnk = 0;
$regional = $mysqli->query($oquery);
	while($r = $regional->fetch_array(MYSQLI_ASSOC))
	{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['ribs'];
			/*
			if($r["teams"]!=0)
			{
				$bbq = $r["banners"]/$r["teams"];
			}
			else
			{
				$bbq = 0;
			}
			*/
			$vars = array($r["reg_key"], $r['year']);
			$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
			$jsvars = str_replace("[","", $jsvars);
			$jsvars = str_replace("]","", $jsvars);
?>

	<tr class="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $rnk;?></td>
		<?php
		if($year!=1)
		{
		?>
			<td class="region"><?php echo $r["reg_name"];?></td>
		<?php
		} else {
		?>
			<td class="region"><?php echo $r["reg_name"]. " " . $r["year"];?></td>
		<?php
		}
		?>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		
	}
?>
	</table>
	</div>
	
	<li id="li_unofficial" class="li_unofficial">
	<span id="collapseView">
		<button type="button" id="unofficial" class="unofficial" onclick="expand('unofficial')">-</button>
		<?php
	if($year!=1)
	{
?>
Unofficial Events
<?php
	} else {
?>
Top 100 Unofficial Events
<?php
}
?>
	</span></li>
	

	<div id="unofficials" class="unofficials">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Event Name</th>
	<th class="region">Total Teams</th>
	<th class="region">RIBS</th>
	
<?php
$regional = $mysqli->query($uquery);
$rnk = 0;
	while($r = $regional->fetch_array(MYSQLI_ASSOC))
	{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['ribs'];
			/*
			if($r["teams"]!=0)
			{
				$bbq = $r["banners"]/$r["teams"];
			}
			else
			{
				$bbq = 0;
			}
			*/
			$vars = array($r["reg_key"], $r['year']);
			$jsvars = json_encode($vars, JSON_HEX_TAG | JSON_HEX_AMP);
			$jsvars = str_replace("[","", $jsvars);
			$jsvars = str_replace("]","", $jsvars);
?>
	<tr class="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td class="region"><?php echo $rnk;?></td>
		<?php
		if($year!=1)
		{
		?>
			<td class="region"><?php echo $r["reg_name"];?></td>
		<?php
		} else {
		?>
			<td class="region"><?php echo $r["reg_name"]. " " . $r["year"];?></td>
		<?php
		}
		?>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		
	}
?>
	</table>
	</div>
</ul>
 </div>
<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>
</html>

<?php
}
?>