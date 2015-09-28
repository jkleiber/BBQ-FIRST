<html>

<head profile="http://www.w3.org/2005/10/profile">
<title>BBQ FIRST - Help</title>
<link rel="icon" 
      type="image/png" 
      href="http://bbqfrc.x10host.com/favicon.png">
<link rel="stylesheet" href="styler.css">
</head>

<script>
var slideheights = [];
function load()
{
	var i;
	for(i=0;i<7;i++)
	{
		var d = document.getElementById("slide_"+i);
		var b = document.getElementById("slide_button_"+i);
		var l = document.getElementById("slide_li_"+i);
		slideheights[i] = d.clientHeight;
		
		d.style.height="0px";
		b.innerHTML = "+";
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 5px;-moz-border-radius-bottomright: 5px;-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;margin-bottom:5px; font-size:18px;"); 
		b.setAttribute("style","-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;");
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
		l.setAttribute("style", "-webkit-border-bottom-right-radius: 5px;-moz-border-radius-bottomright: 5px;-webkit-border-bottom-left-radius: 5px;-moz-border-radius-bottomleft: 5px;margin-bottom:5px; font-size:18px;"); 
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
<body onload="load()">
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
<br>
<ul>
<li class="slideli" id="slide_li_0">
<span id="collapseView">
	<button class="slidebutton" id="slide_button_0" onclick="expand('0')">-</button> What is BBQ FIRST?
</span>
</li>
<div id="slide_0" class="slidediv">
<p>BBQ FIRST is a website that displays statistics of the strength of any given FIRST Robotics Competition event and the success of the competing teams.
Here are some links from Chief Delphi for more information:</p>

<a href="http://www.chiefdelphi.com/forums/showpost.php?p=773426&postcount=27" class="standard_color">Billfred's BBQ post</a>
<br>
<a href="http://www.chiefdelphi.com/forums/showthread.php?threadid=131474" class="standard_color">A recent thread</a>
<br>
<a href="http://www.chiefdelphi.com/forums/showthread.php?t=127716&highlight=Waterloo+Regional+2014" class="standard_color">The thread that inspired this website</a>
<br>
<br>
</div>


<li  class="slideli" id="slide_li_1">
<span id="collapseView">
	<button class="slidebutton" id="slide_button_1" onclick="expand('1')">-</button> The Vocabulary
</span>
</li>	
	<div id="slide_1" class="slidediv">
	
	<table class="help">
		<tr id="helps">
			<th class="help">Term</th>
			<th class="help">What It Stands For</th>
			<th class="help">Description</th>
			<th class="help">The Math</th>
		</tr>
		<tr class="help">
			<td>BBQ</td>
			<td>Blue Banner Quotient</td>
			<td>Statistic for average blue banners per team at a given regional</td>
			<td>BBQ = Total Blue Banners earned by all teams / Total Teams</td>
		</tr>
		<tr class="help">
			<td>SAUCE</td>
			<td>Sextuple Advancement Uniform Counting Era</td>
			<td>This is the statistic for average blue banners per team at a given regional since 2005, when FRC games officially went to 3 team alliances</td>
			<td>SAUCE = Total Blue Banners earned by all teams since 2005 / Total Teams</td>
		</tr>
		<tr class="help">
			<td>BRIQUETTE</td>
			<td>Banner Ratio Indexed to Quadrenniums of Unified Education Total Talent Estimation</td>
			<td>This is the statistic for average blue banners per team at a given regional in the past 4 years, to account for newer teams and to give a better estimation of which teams have been performing better recently.</td>
			<td>BRIQUETTE = Total Blue Banners earned by all teams in the past 4 years / Total Teams</td>
		</tr>
		<tr class="help">
			<td>BBQ PDQ</td>
			<td>Blue Banner Quotient Per Duration Quantity</td>
			<td>Statistic for average blue banners per team per the total amount of years that teams at a given regional have existed</td>
			<td>BBQ PDQ = BBQ / Sum of all years of all teams. Normally a very small number.</td>
		</tr>
<!--		<tr class="help">
			<td>GRILL</td>
			<td></td>
			<td></td>
			<td></td>
		</tr> !-->
		<tr class="help">
			<td>RIB</td>
			<td>Recent Index of Banners</td>
			<td>Statistic that averages all banners earned last year by all teams. For 2005, this statistic does not exist yet.</td>
			<td>RIB = Total Blue Banners earned last year by All Teams / Number of Teams</td>
		</tr>
<!--		<tr class="help">
			<td>PORK</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr class="help">
			<td>STEAK</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	!-->	
	</table>
	</div>
	
	<li  class="slideli" id="slide_li_2">
		<span id="collapseView">
			<button class="slidebutton" id="slide_button_2" onclick="expand('2')">-</button> How the website works
		</span>
	</li>
	<div id="slide_2" class="slidediv">
		<p>The website uses data that is pulled from The Blue Alliance. On an offline server the data is manipulated and is later uploaded to the live website.
		During these data uploads the website may go down, but these uploads ensure that information is displayed faster than recalculating every time
		a page is requested.</p>
	</div>
	
	<li  class="slideli" id="slide_li_6">
		<span id="collapseView">
			<button class="slidebutton" id="slide_button_6" onclick="expand('6')">-</button> The Data
		</span>
	</li>
	<div id="slide_6" class="slidediv">
		<p>The website uses Blue Alliance API data. In order to have the most accurate data possible, we calculate team banner counts on a weekly basis during competition season. 
		We also classify each event into specific week categories in order to figure out which data goes where. Offseason event victories do not contribute to Blue Banners</p>
	</div>
	
	<li  class="slideli" id="slide_li_3">
		<span id="collapseView">
			<button class="slidebutton" id="slide_button_3" onclick="expand('3')">-</button> Main
		</span>
	</li>
	<div id="slide_3" class="slidediv">
		<p>The Main sub-menu contains 'FRC Event Browser By Year' where you can see a list of every FRC event within the years 2005 and 2015.
		The sub-menu also has 'FRC Team Search Utility' which lets you look up teams and their success by year.
		The 'Top 10' feature lists out the teams with the most blue banners.</p>
	</div>
	
	<li  class="slideli" id="slide_li_4">
		<span id="collapseView">
			<button class="slidebutton" id="slide_button_4" onclick="expand('4')">-</button> Rankings
		</span>
	</li>
	<div id="slide_4" class="slidediv">
		<p>The Rankings sub-menu provides four types of event rankings - BBQ, SAUCE, BRIQUETTE and RIB. Each ranks events for a specific year or all time, and provides divisions
		for Overall Top 25, Official, and Unofficial events.</p>
	</div>
	
	<li  class="slideli" id="slide_li_5">
		<span id="collapseView">
			<button class="slidebutton" id="slide_button_5" onclick="expand('5')">-</button> Labs
		</span>
	</li>
	<div id="slide_5" class="slidediv">
		<p>The Labs sub-menu allows you to run your own experiments with our data. You can build an ideal regional with the Manual Calculator and share the link. There will be more labs to come.</p>
	</div>
	</ul>

	
	<p>If you have any questions that aren't covered here, <a href="contact.php" class="standard">Contact Us</a>.</p>
	</div>
		<footer class="nav" class="site-footer">
				<a href="admin/" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a>
		</footer>
</body>

</html>