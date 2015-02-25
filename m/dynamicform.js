/* set global variable i */
 
var i=0;
 
function increment(){
i +=1; /* function for automatic increment of field's "Name" attribute*/
}

function addTeamBox()
{
var r=document.createElement('span');
var b=document.createElement('br');
var y = document.createElement("INPUT");
var a = document.createElement('a');
a.innerHTML = "X";
//d.setAttribute("type", "button");
a.setAttribute("id", "db_"+i);
a.setAttribute("class", "db");
a.setAttribute("href", "#");
y.setAttribute("type", "number");
y.setAttribute("id", "y_"+i);
y.setAttribute("placeholder","Enter team #");
y.setAttribute("name","teams" +i);
y.setAttribute("min","1");
y.setAttribute("onkeydown", "keysGotPressed(event, 'id_"+i+"')");
a.setAttribute("onclick", "deleteBox('myForm', 'id_"+i+"')");
r.appendChild(y);
r.appendChild(a);
r.appendChild(b);
r.setAttribute("id", "id_"+i);
increment();
document.getElementById("myForm").appendChild(r);
}

function deleteBox(parentDiv, childDiv)
{
if(i>1)
{
	if (childDiv == parentDiv) {
	alert("The parent element cannot be removed.");
	}
	else if (document.getElementById(childDiv)) {
	var child = document.getElementById(childDiv);
	var parent = document.getElementById(parentDiv);
	parent.removeChild(child);
	}
	else {
	alert("Child element has already been removed or does not exist.");
	return false;
	}
}
}

function keysGotPressed(event, id)
{
	
		if (event.keyCode == 9)
		{ 
			document.getElementById('addButton').click(); 
		}
	
	if(i > 1)
	{	
		if (event.keyCode == 46 )
		{ 
			deleteBox("myForm", id); 
		}
	}
}

