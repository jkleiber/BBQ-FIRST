var width = 0;

$(document).ready(function()
{
	console.log("loaded!");
	var d = document.getElementById("search_bar");
	
	width = d.clientWidth;
	
	console.log(width);
	
	//d.style.width = "0px";
});

function expandSearch()
{
	var d = document.getElementById("search_bar");
	
	
	
	if(d.clientWidth == 0)
	{
		d.setAttribute("style","width: " + width + "px");
	}
	else
	{
		d.style.width = "0px";
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