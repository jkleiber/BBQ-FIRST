<html>

<script src="jquery-1.11.1.min.js"></script>
<script>
var regArray = new Array();

function jsonget(url)
{
	
	$.getJSON(url, function(data){
		$.each(data, function (index, value) {
			console.log(value);
			regArray.push(value);
			$('#events').append("<div>"+regArray.length+"</div>");
		});
		
	});
}

$(document).ready(function() 
{ 
	jsonget("eventlist.php?year=2015");
	
	$('#events').append("<div>"+regArray.length+"</div>");
	for(var i=0;i<regArray.length;i++)
	{
		$('#events').append("<div>1</div>");
	} 
	
});

$('#search').change(function()
{
	$('#events').value("");
	var s = $('#search').innerHTML;
	for(var i=0;i<regArray.length;i++)
	{
		if(s.indexOf(regArray[i]) != -1)
		{
			$('#events').append("<div>"+regArray[i] +"<div>");
		}
	} 
});

</script>

<input id="search" type="text">

<div id="events">

</div>

</html>