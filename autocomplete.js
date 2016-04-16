var currentHover=-1;

function hoverMove(amt)
{
	if($("#results").children("a").length > 0)
	{
		$("#results").children("a").eq(currentHover).children("div").eq(0).removeClass("hover");
		
		currentHover += amt;
		
		if(currentHover < 0)
		{
			currentHover = $("#results").children("a").length - 1;
		}
		if(currentHover >= $("#results").children("a").length)
		{
			currentHover = 0;
		}
		
		$("#results").children("a").eq(currentHover).children("div").eq(0).addClass("hover");
		
		//console.log($("#results").children("a").eq(currentHover).attr("href"));
	}
}
var old_length=0;
var MAX_ELEMENTS = 10;
$( document ).ready(function() {
	$("#searcher").keyup(function() {
		var keyword = $("#searcher").val();
		if (keyword.length > 0) {
			
			var list = [];
			/*
			if(old_length != keyword.length && currentHover >= $("#results").children("a").length)
			{
				currentHover = 0;
				old_length = keyword.length;
			}
			if(old_length != keyword.length)
			{
				old_length = keyword.length;
			}*/
			//currentHover = 0;
			
			$.get( "auto_completion.php", { keyword: keyword } )
			.done(function( data ) {
					//console.log(data);
					$('#results').html('');
					var results = jQuery.parseJSON(data);
					for(var id=0;id<results.length;id++)
					{
						var items = [];
						items[0] = results[id]['display'];
						items[1] = results[id]['link'];
						
						list.push(items);
					}
					
					var len = 0;
					if(results.length > MAX_ELEMENTS)
					{
						len = MAX_ELEMENTS;
					}
					else
					{
						len=results.length;
					}
					
					for(var index=0;index<len;index++)
					{
						$('#results').append('<a class="invisible" href='+ list[index][1]+'><div class="item">' + list[index][0] + '</div></a>');
					}
					
					if(currentHover >= 0)
					{
						$("#results").children("a").eq(currentHover).children("div").eq(0).addClass("hover");
					}
					
					$('.item').click(function() {
						var text = $(this).html();
						$('#keyword').val(text);
					});
			});
		} 
		else
		{
			$('#results').html('');
		}
		
		console.log(currentHover);
		
		if(event.keyCode==38)
		{
			//Up
			hoverMove(-1);
		}
		if(event.keyCode==40)
		{
			//Down
			hoverMove(1);
		}
		if(event.keyCode==13)
		{
			//Enter pressed, simulate clicking the link
			if(currentHover >= 0)
			{
				location.href = $("#results").children("a").eq(currentHover).attr("href");
			}
			else
			{
				location.href = "./search_page.php?q=" + keyword;
			}
		}
	});
	
	 $("#keyword").blur(function(){
    		$("#results").fadeOut(500);
    	})
        .focus(function() {		
    	    $("#results").show();
    	});
		
		$("#results").on('mouseenter', 'li', function(){
			$(this).addClass('hover');
		}).on('mouseleave', 'li', function(){
			$(this).removeClass('hover');
		});
});