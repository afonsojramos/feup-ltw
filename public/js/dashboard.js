
let body = document.getElementById("dashboardContainer");
//----------------------------------------------sidebar functions
let sidebar = document.getElementById("mySidenav");

function toggleSideBar() {
    if (sidebar.style.width != "250px") {
		openNav();
	}else{
		closeNav();
	}
}
function openNav() {
	sidebar.style.width = "250px";
}
function closeNav() {
	sidebar.style.width = "0";
}

body.addEventListener("click", function () {//close the sidebar when there is a clique
	closeNav();
}, false);
sidebar.addEventListener("click", function (e) {//if the click is in the same
	e.stopPropagation();
}, false);