window.onload = function()
{
	document.body.style.display = "block";
	document.getElementsByClassName("navbar_ico")[0].style.display = "hidden";
}
function openNavbar()
{
	var x = document.getElementById("_navbar");
	if(x.className === "navbar")
	{
		x.className += " responsive";
	}
	else
	{
		x.className = "navbar";
	}
}
function openNav() {
  document.getElementById("_sidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("_sidenav").style.width = "0";
}