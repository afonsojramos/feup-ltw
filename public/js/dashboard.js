let body = document.getElementById("dashboardContainer");

document.addEventListener('keydown', (e) => { //on keyboard press
	if (e.keyCode == 27) { //Esc
		closeSideBar();
		clearSearch();
	} else if (e.ctrlKey && e.keyCode == 70) { //Ctrl+f
		enterSearch(e);
	} else if (e.ctrlKey && e.keyCode == 83) { //Ctrl+s
		toggleSideBar();
		e.preventDefault();
	} else if (e.keyCode == 13) { //Enter
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


//----------------------------------------------todoItems
let todoItems = document.getElementsByClassName("todoItem");
Array.prototype.forEach.call(todoItems, function (checkbox) {
	checkbox.addEventListener("change", function (e) {//when a checkbox state changes
		let parentTodo = findParentByClass(checkbox, "todo");//get the <div class="todo"> above
		let data = {
			itemId: checkbox.getAttribute("data-itemId"),
			// todoListId: checkbox.getAttribute("data-todoListId"),
			completed: checkbox.checked
		};
		request("actions/edit_item_checked.php", function (data) {
			console.log(data);
			if (!data.success) {
				addErrorModalMessages(parentTodo, data.errors);
			}
		}, data, "post");
	});
});