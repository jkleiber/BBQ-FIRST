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
$key = $_GET['key'];
$ekey = substr($key, 4);
$ekey = strtoupper($ekey);

$teams = file_get_contents("http://www.thebluealliance.com/api/v2/event/". $key ."/teams?X-TBA-App-Id=justin_kleiber:team_scraper:1");
$teamlist=json_decode($teams,true);

$tems = array();
foreach($teamlist as $t)
{
	$tems[]=$t['team_number'];
}
$in = implode(',',$tems);
$stmt = "SELECT * FROM `bbqfrcx1_db`.`2015` WHERE `team_num` IN(".$in.") ORDER BY `cmp` DESC LIMIT 10";
$resu = $mysqli->query($stmt) or trigger_error($mysqli->error."[$stmt]");;

$query = "SELECT * FROM `bbqfrcx1_db`.`regional_info` WHERE `reg_key`='$key'";
$result = $mysqli->query($query);
$ro = mysqli_fetch_assoc($result);

$que= "SELECT * FROM `bbqfrcx1_db`.`regional_data` WHERE `reg_key`='$key'";
$res = $mysqli->query($que);
$roww = mysqli_fetch_assoc($res);
/*
$html = file_get_contents('http://www2.usfirst.org/2014comp/events/TXHO/matchresults.html');
$dom = new DOMDocument();
@$dom->loadHTML($html);
*/

?>

<html>
	
	
	<head profile="http://www.w3.org/2005/10/profile">
	<title>BBQ EventCast - <?php echo $ro['reg_name']; ?></title>
	<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
	<link rel="stylesheet" href="styler.css">
	<script src="jquery-1.11.1.min.js"></script>
	</head>
	
<script>
var mode = 0;
var old_mode = 2;
var i = 3;
var r = 2;
var firstrun=true;
var ekey;
var refresher;
var left_refresh;
var right_refresh;
var rowArray = new Array();

$(document).ready(function() 
	{ 
	
	ekey = $('#key').html();
		$.getJSON("gettablerows.php?key="+ekey, function(data){
			$.each(data, function (index, value) {
				//console.log(value);
				rowArray.push(value);
			});
			
		refresher = interval();
		left_refresh = left_interval();
		right_refresh = right_interval();
	});
	
});

function clicker(){
$("#webTicker").slideUp();
clearInterval(refresher);
  if(mode<2)
	{
		mode++;
				
	}
	else
	{
		mode=0;
	}
	i=3;
	r=2;
	if(mode==0)
	{
		$('#slide_title').attr('src','qm_boxicon.png');
	}
	else if(mode==1)
	{
		$('#slide_title').attr('src','pm_boxicon.png');
	}
	else if(mode==2)
	{
		$('#slide_title').attr('src','s_boxicon.png');
	}
	
	refresher = interval();
}

function interval()
{
    return setInterval(function(){

		checkMode();
		
		var matchArray = new Array();
		if(mode==0 && i<rowArray[0])
		{
			
		$.getJSON("getmatch.php?i="+i+"&key="+ekey, function(data){
			$.each(data, function (index, value) {
				//console.log(value);
				matchArray.push(value);
			});
			$('#d1').html(matchArray[1]);
			var red = matchArray[2] + " " + matchArray[3] + " " + matchArray[4];
			$('#d2').html(red);
			var blue = matchArray[5] + " " + matchArray[6] + " " + matchArray[7];
			$('#d5').html(blue);
			$('#d3').html(matchArray[8]);
			$('#d4').html(matchArray[9]);
			//console.log(i);
		});
		
		var w = $( window ).width() - $('#webTicker').width()-173;
		//console.log(w);
		/*
		$('#webTicker').animate(
		{
			right:"+="+w+"px"
		},4000);
		$('#webTicker').animate(
		{
			//opacity:"0"
			right:"+=0"
		},5950);
		$('#webTicker').animate(
		{
			right:"0",
			opacity:"0"
		},50); */
		$("#webTicker").slideDown();
		setTimeout(function(){
		$("#webTicker").slideUp();
		}, 7000);
		/*
		$('#webTicker').animate(
		{
			right:"0",
			display:"inline"
		},0);
		*/
		//$("#webTicker").slideDown();
		i++;
		}
		else if(mode==1 && i<rowArray[1])
		{
			
			$.getJSON("getelim.php?i="+i+"&key="+ekey, function(data){
			$.each(data, function (index, value) {
				//console.log(value);
				matchArray.push(value);
			});
			$('#d1').html(matchArray[1]);
			var red = matchArray[3] + " " + matchArray[4] + " " + matchArray[5];
			$('#d2').html(red);
			var blue = matchArray[6] + " " + matchArray[7] + " " + matchArray[8];
			$('#d5').html(blue);
			$('#d3').html(matchArray[9]);
			$('#d4').html(matchArray[10]);
			//console.log(i);
		});
		
		var w = $( window ).width() - $('#webTicker').width()-173;
		//console.log(w);
		/*
		$('#webTicker').animate(
		{
			right:"+="+w+"px"
		},4000);
		$('#webTicker').animate(
		{
			//opacity:"0"
			right:"+=0"
		},5950);
		$('#webTicker').animate(
		{
			right:"0",
			opacity:"0"
		},50); */
		$("#webTicker").slideDown();
		setTimeout(function(){
		$("#webTicker").slideUp();
		}, 7000);
		/*
		$('#webTicker').animate(
		{
			right:"0",
			display:"inline"
		},0);
		*/
		//$("#webTicker").slideDown();
		i++;
		}
		else if(mode==2 && r<rowArray[2])
		{
			
		
			$.getJSON("getranks.php?t="+r+"&key="+ekey, function(data){
			$.each(data, function (index, value) {
				//console.log(value);
				matchArray.push(value);
			});
			$('#d1').html(matchArray[0]);
			var red = matchArray[1];
			$('#d2').html(red);
			var blue = matchArray[4] + " " + matchArray[5]+ " " + matchArray[6];
			//var blue =  matchArray[3];
			$('#d5').html(blue);
			$('#d3').html(matchArray[2]);
			$('#d4').html(matchArray[9]);
			//console.log(i);
		});
		
		//var w = $( window ).width() - 640;
		var w = $( window ).width() - $('#webTicker').width()-173;
		//console.log(w);
		/*
		$('#webTicker').animate(
		{
			right:"+="+w+"px"
		},4000);
		$('#webTicker').animate(
		{
			//opacity:"0"
			right:"+=0"
		},5950);
		$('#webTicker').animate(
		{
			right:"0",
			opacity:"0"
		},50); */
		$("#webTicker").slideDown();
		setTimeout(function(){
		$("#webTicker").slideUp();
		}, 7000);
		/*
		$('#webTicker').animate(
		{
			right:"0",
			display:"inline"
		},0);
		*/
		//$("#webTicker").slideDown();
		r++;
		}
		else
		{
		
			if(mode<2)
			{
				mode++;
				
			}
			else
			{
				mode=0;
			}
			i=3;
			r=2;
		}
		
		firstrun=false;
	},10000); 
}


var li = 0;
function left_interval()
{
	return setInterval(function(){
		
		//$("#l_"+li).show();
		
		if(li==0)
		{
			for(var rr=0; rr<8; rr++)
			{
			(function(rr) {
				var tm = rr+2;
				var rankArray = new Array();
				var temArray = new Array();
				$.getJSON("getranks.php?t="+tm+"&key="+ekey, function(data){
				$.each(data, function (index, value) {
					//console.log(value);
					rankArray.push(value);
				});
				
				$("#num"+rr).html(rankArray[1]);				
				$("#qa"+rr).html(rankArray[2]);
				
					$.getJSON("getname.php?num="+rankArray[1], function(data){
					$.each(data, function (index, value) {
						//console.log(value);
						temArray.push(value);
					});
					
					$("#name"+rr).html(temArray[0]);
					});
				
				});
				
			})(rr);
			}
		}
		
		$("#l_"+li).slideDown();
		setTimeout(function(){
		$("#l_"+li).slideUp();
			if(li<2)
			{
				li++;
			}
			else
			{
				li=0;
			}
		}, 14000);
		
		//console.log(li);
		
	},15000);
	
}


var ri = 0;
function right_interval()
{
	return setInterval(function(){
		
		//$("#l_"+li).show();
		//console.log(ri);
		if(ri==0)
		{
			//console.log("getStats.php?key=2014"+ekey);
			var statArray = new Array();
			$.getJSON("getStats.php?key=2014"+ekey, function(data){
				$.each(data, function (index, value) {
					statArray.push(value);
				});
				
				$("#opr_num").html(statArray[0]);
				$("#opr_val").html(statArray[1]);
				$("#ccwm_num").html(statArray[2]);
				$("#ccwm_val").html(statArray[3]);
			});
		}
		else if(ri==1)
		{
			var statArray = new Array();
			$.getJSON("gethighscore.php?key="+ekey, function(data){
				$.each(data, function (index, value) {
					statArray.push(value);
				});
				
				$("#match_num").html(statArray[0]);
				$("#win_allies").html(statArray[1] + ", " + statArray[2] + ", " + statArray[3]);
				$("#win_score").html(statArray[4]);
			});
		}
		else if(ri==2)
		{
			var statArray = new Array();
			$.getJSON("topscorers.php?key="+ekey, function(data){
				$.each(data, function (index, value) {
					statArray.push(value);
				});
				
				$("#coop_team_num").html(statArray[0][0]);
				$("#coop_points").html(statArray[0][1]);
				$("#auto_team_num").html(statArray[1][0]);
				$("#auto_points").html(statArray[1][1]);
			});
		}
		
		$("#r_"+ri).slideDown();
		setTimeout(function(){
			
			$("#r_"+ri).slideUp();
			if(ri<2)
			{
				ri++;
			}
			else
			{
				ri=0;
			}
			
		}, 14000);
		
		//console.log(li);
		
	},15000);
	
}

function checkMode()
{
if(old_mode!=mode||firstrun)
{
		
		if(mode==0)
		{
			$('#slide_title').attr('src','qm_boxicon.png');
			//$('#webTicker').toggleClass("qtick");
			//$('#webTicker').removeClass( "stick" ).addClass( "qtick" );
			$('#d1').removeClass( "pgreen" ).addClass( "match" );
			$('#d2').removeClass( "pgreen" ).addClass( "red" );
			$('#d5').removeClass( "winners" ).addClass( "blue" );
			//$('#d1').toggleClass("match");
			//$('#d2').toggleClass("red");
			//$('#d3').toggleClass("winner");
			//$('#d4').toggleClass("winner");
			//$('#d5').toggleClass("blue");
			$('#h1').html("Match #");
			$('#h2').html("Red");
			$('#h3').html("Score");
			$('#h4').html("Score");
			$('#h5').html("Blue");
		}
		else if(mode==1)
		{
			$('#slide_title').attr('src','pm_boxicon.png');
			//$('#webTicker').removeClass( "qtick" ).addClass( "etick" );
			$('#h1').html("Match");
			$('#h2').html("Red");
			$('#h3').html("Score");
			$('#h4').html("Score");
			$('#h5').html("Blue");
		}
		else if(mode==2)
		{
			$('#slide_title').attr('src','s_boxicon.png');
			//$('#webTicker').toggleClass("stick");
			$('#d1').removeClass( "match" ).addClass( "pgreen" );
			$('#d2').removeClass( "red" ).addClass( "pgreen" );
			$('#d5').removeClass( "blue" ).addClass( "winners" );
			//$('#d3').toggleClass("winner");
			//$('#d4').toggleClass("winner");
			$('#h1').html("Place");
			$('#h2').html("Team #");
			$('#h3').html("QS");
			$('#h4').html("Played");
			$('#h5').html("Co-Op, Auto, Teleop");
		}
		old_mode=mode;
	}
}
function linktoteam(key, yr)
{
	//location.href = 'http://www.bbqfrc.x10host.com/team_info.php?tem=' + key + "&year=" +yr;
	location.href = 'http://localhost/BBQ FIRST/team_info.php?tem=' + key + "&year=" +yr;
}
</script>
	
	<body style="background-color:#000">
		<div id="container">
		
		
			<div class="nav">
				<a href="index.php" class="nav"></a>
			</div>
			<div class="stats">
				<table class="stats">
					<tr>
						<td >
							<a id="key" class="subtle_link" href="http://www.bbqfrc.x10host.com/event_info.php?key=<?php echo $key?>&year=2015"><?php echo $ekey; ?></a>
						</td>
						<td>
							<?php echo "BBQ: ".$roww['bbq']; ?>
						</td>
						<td>
							<?php echo "SAUCE: ".$roww['sauce']; ?>
						</td>
						<td>
							<?php echo "BRIQUETTE: ".$roww['briquette']; ?>
						</td>
						<td>
							<?php echo "RIBS: ".$roww['ribs']; ?>
						</td>
						<td>
							<?php echo "BBQ PDQ: ".$roww['bbq_pdq']; ?>
						</td>
					</tr>
				</table>
			</div>
			<h2><?php echo $ro['reg_name']; ?></h2>
		
			<div style="width:99%">
				<div class="lefthalf">
					<div class="innerdiv">
					<br>
						<div id="l_0">
						<u>Standings</u>
						<table class="standings">
							<th class="standings">Place</th>
							<th class="standings">Team Name</th>
							<th class="standings">Team Number</th>
							<th class="standings">Qualifying Average</th>
							<tr>
								<td id="p0">1</td>
								<td id="name0"></td>
								<td id="num0"></td>
								<td id="qa0"></td>
							</tr>
							<tr>
								<td id="p1">2</td>
								<td id="name1"></td>
								<td id="num1"></td>
								<td id="qa1"></td>
							</tr>
							<tr>
								<td id="p2">3</td>
								<td id="name2"></td>
								<td id="num2"></td>
								<td id="qa2"></td>
							</tr>
							<tr>
								<td id="p3">4</td>
								<td id="name3"></td>
								<td id="num3"></td>
								<td id="qa3"></td>
							</tr>
							<tr>
								<td id="p4">5</td>
								<td id="name4"></td>
								<td id="num4"></td>
								<td id="qa4"></td>
							</tr>
							<tr>
								<td id="p5">6</td>
								<td id="name5"></td>
								<td id="num5"></td>
								<td id="qa5"></td>
							</tr>
							<tr>
								<td id="p6">7</td>
								<td id="name6"></td>
								<td id="num6"></td>
								<td id="qa6"></td>
							</tr>
							<tr>
								<td id="p7">8</td>
								<td id="name7"></td>
								<td id="num7"></td>
								<td id="qa7"></td>
							</tr>
						</table>
						</div>
						<div id="l_1" style="display:none">
						Playoff Standings
						</div>
						<div id="l_2" style="display:none">
						Place Holder 2
						</div>
					</div>
				</div>
				<div class="righthalf">
					<div class="innerdiv">
					-- ..
					<br>
						<div id="r_0">
						<u>Highest OPR</u>
						<br>
						<br>
							<table class="opr">
							<th>Team #</th>
							<th>OPR</th>
								<tr>
									<td id="opr_num"></td>
									<td id="opr_val"></td>
								</tr>
							</table>
							<br><br><br><br>
						<u>Highest CCWM</u>
						<br>
						<br>
							<table class="opr">
							<th>Team #</th>
							<th>CCWM</th>
								<tr>
									<td id="ccwm_num"></td>
									<td id="ccwm_val"></td>
								</tr>
							</table>
						</div>
						
						<div id="r_1" style="display:none">
							<u>Best Single Alliance Score</u>
							<br>
							<br>
							<table class="opr">
							<th>Match #</th>
							<th>Winning Alliance</th>
							<th>Score</th>
								<tr>
									<td id="match_num"></td>
									<td id="win_allies"></td>
									<td id="win_score"></td>
								</tr>
							</table>
						</div>
						
						<div id="r_2" style="display:none">
							<u>Best Coopertition Stacker</u>
							<br>
							<br>
							<table class="opr">
							<th>Team #</th>
							<th>Points</th>
								<tr>
									<td id="coop_team_num"></td>
									<td id="coop_points"></td>
								</tr>
							</table>
							
							<u>Best Overall Auto</u>
							<br>
							<br>
							<table class="opr">
							<th>Team #</th>
							<th>Points</th>
								<tr>
									<td id="auto_team_num"></td>
									<td id="auto_points"></td>
								</tr>
							</table>
							
						</div>
					</div>
				</div>
			</div>
			<div>
				<table style="text-align:center;width:100%">
					<tr>
						<td>
							<a class="bottom-link" href="http://www2.usfirst.org/2014comp/Events/<?php echo $ekey; ?>/rankings.html">Full Standings</a>
						</td>
						<td>
							<a class="bottom-link" href="http://www2.usfirst.org/2014comp/Events/<?php echo $ekey; ?>/matchresults.html">Full Match List</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<footer>
		
		<span id="webTicker">
		<table id="ticker">
			<thead>
				<th id="h1"></th>
				<th id="h2"></th>
				<th id="h3"></th>
				<th id="h4"></th>
				<th id="h5"></th>
			</thead>
			<tbody>
				<tr >
					<td id="d1"></td>
					<td id="d2"></td>
					<td id="d3" class="winner">Loading</td>
					<td id="d4" class="winner">...</td>
					<td id="d5"></td>
				</tr>
			</tbody>
		</table>
		</span>
		<img id="slide_title" src="qm_boxicon.png" onclick="clicker()" style="float:left;cursor: pointer;"/>
		</footer>
	</body>
</html>
<?php
}
?>