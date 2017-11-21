let body = document.getElementById("dashboardContainer");

document.addEventListener('keydown', (e) => {//on keyboard press
	if (e.keyCode == 27) { //Esc
		closeSideBar();
		clearSearch();
	}else if(e.keyCode == 70 && e.ctrlKey){//Ctrl+f
		enterSearch(e);
	}else if(e.keyCode == 13){//Enter
		doSearch();
	}
});

//----------------------------------------------sidebar functions

let sidebar = document.getElementById("mySidenav");

function toggleSideBar() {
	if (sidebar.style.width != "250px") {
		openSideBar();
	} else {
		closeSideBar();
	}
}

function openSideBar() {
	sidebar.style.width = "250px";
}

function closeSideBar() {
	sidebar.style.width = "0";
}

body.addEventListener("click", function () { //close the sidebar when there is a clique
	closeSideBar();
}, false);
sidebar.addEventListener("click", function (e) { //if the click is in the same
	e.stopPropagation();
}, false);