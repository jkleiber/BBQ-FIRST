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
	<div id="nav">
			<a href="index.html" id="nav">
			</a> 
			<a href="help.html" id="nav_txt">
				Help	
			</a> 
	</div>
	<?php
	error_reporting(E_ALL ^ E_NOTICE);
	if($_GET['year'])
	{
		$year=$_GET['year'];
	}
	else
	{
		$year=2015;
	}
?>
	
<?php
	$mysqli = new mysqli('localhost','bbqfirst_admin','bbqpass', "bbqfirst_db");
	
	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}
	
	$query = "SELECT * FROM `bbqfirst_db`.`regionaldata` WHERE `year`='$year' ORDER BY `regionaldata`.`bbq` DESC, `teams` DESC";
	$que = "SELECT * FROM `bbqfirst_db`.`regionaldata` WHERE `year`='$year' ORDER BY `regionaldata`.`bbq` DESC, `teams` DESC LIMIT 25";
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
function subform(){
		document.getElementById("yrs").submit();
	}
</script>
<form method="get" id="yrs">
<select name="year" onchange="subform()">
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
<li  id="li_top">
<span id="collapseView">

<button id="top" type="button" onclick="expand('top')">-</button>
<!-- 	<a id="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
Overall Top 25 Events
</span>
</li>

	<div id="tops">
	<table id="region">
	<th id="region">Rank</th>
	<th id="region">Regional Name</th>
	<th id="region">Total Teams</th>
	<th id="region">BBQ</th>
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

	<tr id="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td id="region"><?php echo $rnk;?></td>
		<td id="region"><?php echo $r["reg_name"];?></td>
		<td id="region"><?php echo $r["teams"];?></td>
		<td id="region"><?php echo $bbq?></td>
	</tr>
	
<?php
	}
?>
	</table>
	</div>
	

<li  id="li_official">
<span id="collapseView">

<button id="official" type="button" onclick="expand('official')">-</button>
<!-- 	<a id="official" onclick="expand('official')"><img id="pm" src="minus.png"/></a> !-->
Official Events
</span>
</li>

	<div id="officials">
	<table id="region">
	<th id="region">Rank</th>
	<th id="region">Regional Name</th>
	<th id="region">Total Teams</th>
	<th id="region">BBQ</th>
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

	<tr id="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td id="region"><?php echo $rnk;?></td>
		<td id="region"><?php echo $r["reg_name"];?></td>
		<td id="region"><?php echo $r["teams"];?></td>
		<td id="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		}
	}
?>
	</table>
	</div>
	
	<li id="li_unofficial">
	<span id="collapseView">
		<button id="unofficial" type="button" onclick="expand('unofficial')">-</button>
		Unofficial Events
	</span></li>
	

	<div id="unofficials">
	<table id="region">
	<th id="region">Rank</th>
	<th id="region">Event Name</th>
	<th id="region">Total Teams</th>
	<th id="region">BBQ</th>
	
<?php
$regional = $mysqli->query($query);
$rnk = 0;
	while($r = $regional->fetch_array(MYSQLI_ASSOC))
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
	<tr id="regions" onclick='linktoevent(<?php echo $jsvars; ?>)'>
		<td id="region"><?php echo $rnk;?></td>
		<td id="region"><?php echo $r["reg_name"];?></td>
		<td id="region"><?php echo $r["teams"];?></td>
		<td id="region"><?php echo $bbq?></td>
	</tr>
	
<?php
		}
	}
?>
	</table>
	</div>
</ul>
</body>
</html>