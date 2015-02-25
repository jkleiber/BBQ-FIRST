<?php
$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
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
?>

<html>
<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Event Rankings</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfirst.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
<link rel="stylesheet" href="mobile_styler.css">
<link rel="stylesheet" href="small_styler.css">
</head>
<body onload="load()">
<div id="container">
	<div class="nav">
			<a href="index.php" class="nav">
			</a> 
			<a href="help.php" class="nav_txt">
				Help	
			</a> 
	</div>
<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$query = "SELECT * FROM `bbqfirst_db`.`regionaldata` WHERE `sponsored`='official' ORDER BY `regionaldata`.`bbq` DESC, `teams` DESC LIMIT 50";
	$off = "SELECT * FROM `bbqfirst_db`.`regionaldata` WHERE `sponsored`='unofficial' ORDER BY `regionaldata`.`bbq` DESC, `teams` DESC LIMIT 50";
	
	
	$que = "SELECT * FROM `bbqfirst_db`.`regionaldata` ORDER BY `regionaldata`.`bbq` DESC, `teams` DESC LIMIT 25";
	$top = $mysqli->query($que);
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
	
		step(1,function(){ 
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
	location.href = 'event_info.php?key=' + key + "&year=" +yr;
}
</script>
<p id="p">

</p>
<ul>
<li  class="li_top">
<span id="collapseView">

<button class="top" onclick="expand('top')">-</button>
<!-- 	<a class="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
Overall Top 25 Events
</span>
</li>

	<div class="tops">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Regional Name</th>
	<th class="region">Total Teams</th>
	<th class="region">BBQ</th>
<?php
$rnk = 0;
	while($r = $top->fetch_array(MYSQLI_ASSOC))
	{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['bbq'];
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
		<td class="region"><?php echo $r["reg_name"]." ".$r['year'];?></td>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
	}
?>
	</table>
	</div>
	

<li  class="li_official">
<span id="collapseView">

<button class="official" onclick="expand('official')">-</button>
<!-- 	<a class="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
Top 50 Official Events
</span>
</li>

	<div class="officials">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Regional Name</th>
	<th class="region">Total Teams</th>
	<th class="region">BBQ</th>
<?php
$rnk = 0;
$regional = $mysqli->query($query);
	while($r = $regional->fetch_array(MYSQLI_ASSOC))
	{
		if($r["sponsored"] == "official")
		{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['bbq'];
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
		<td class="region"><?php echo $r["reg_name"]." ".$r['year'];?></td>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		}
	}
?>
	</table>
	</div>
	
	<li class="li_unofficial">
	<span id="collapseView">
		<button class="unofficial" onclick="expand('unofficial')">-</button>
		Top 50 Unofficial Events
	</span></li>
	

	<div class="unofficials">
	<table class="region">
	<th class="region">Rank</th>
	<th class="region">Event Name</th>
	<th class="region">Total Teams</th>
	<th class="region">BBQ</th>
	
<?php
$regional = $mysqli->query($query);
$rnk = 0;
$offsea = $mysqli->query($off);
	while($r = $offsea->fetch_array(MYSQLI_ASSOC))
	{
		if($r["sponsored"] != "official")
		{
			$bbq = 0;
			$rnk ++;
			$bbq = $r['bbq'];
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
		<td class="region"><?php echo $r["reg_name"]." ".$r["year"];?></td>
		<td class="region"><?php echo $r["teams"];?></td>
		<td class="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		}
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