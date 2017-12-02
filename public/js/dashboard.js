let body = document.getElementById("dashboardContainer");

document.addEventListener('keydown', (e) => { //on keyboard press
	if (e.keyCode == 27) { //Esc
		closeSideBar();
		clearSearch();
	} else if (e.ctrlKey && e.keyCode == 70) { //Ctrl+f -> open search
		enterSearch(e);
	} else if (e.ctrlKey && e.keyCode == 83) { //Ctrl+s -> show sidebar
		toggleSideBar();
		e.preventDefault();
	} else if (e.ctrlKey && e.keyCode == 65) { //Ctrl+a -> open modal
		document.getElementById("openAddListModal").click();
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
//change checked state of an item
let todoCheckboxes = document.getElementsByClassName("todoItem");
Array.prototype.forEach.call(todoCheckboxes, function (checkbox) {
	checkbox.addEventListener("change", function (e) { //when a checkbox state changes
		let parentTodo = findParentByClass(checkbox, "todo"); //get the <div class="todo"> above
		let data = {
			itemId: checkbox.getAttribute("data-itemId"),
			completed: checkbox.checked,
			action: "completed"
		};
		request("actions/edit_item.php", function (data) {
			console.log(data);
			if (!data.success) {
				addErrorMessage(parentTodo, data.errors);
			}
		}, data, "post");
	});
});

//---------using editableOnClick start
//add a new item to a TodoList
let todoItemLabels = document.getElementsByClassName("todoItemLabel");
Array.prototype.forEach.call(todoItemLabels, function (item) {
	let myItem, parentTodo = findParentByClass(item, "todo"); //get the <div class="todo"> above
	item.addEventListener("click", function (e) {
		let updateText = function (textBox) {
			let data = {
				itemId: item.getAttribute("data-itemId"),
				action: "content",
				content: textBox.value
			};
			myItem.doRequest("actions/edit_item.php", data, parentTodo);
		};
		let textBox = document.getElementById("editItem_" + item.getAttribute("data-itemId")).cloneNode();
		myItem = new editableOnClick(item, textBox, updateText);
	});
});
//edit the TodoLists' title
let todolistsTitles = document.getElementsByClassName("todoTitle");
Array.prototype.forEach.call(todolistsTitles, function (title) {
	title.addEventListener("click", function (e) { //when a checkbox state changes
		let myItem, parentTodo = findParentByClass(title, "todo"); //get the <div class="todo"> above
		let updateText = function (textBox) {
			let data = {
				todoListId: title.getAttribute("data-todoListId"),
				title: textBox.value
			};
			myItem.doRequest("actions/edit_list.php", data, parentTodo);
		};
		let textBox = document.getElementById("editTitle_" + parentTodo.getAttribute("data-todoListId")).cloneNode();
		myItem = new editableOnClick(title, textBox, updateText);
	});
});
let todoListNewItems = document.getElementsByClassName("addItemText");
Array.prototype.forEach.call(todoListNewItems, function (newItem) {
	newItem.addEventListener("click", function (e) { //when a checkbox state changes
		let myItem, parentTodo = findParentByClass(newItem, "todo"); //get the <div class="todo"> above
		let updateText = function (textBox) {
			let data = {
				todoListId: newItem.getAttribute("data-todoListId"),
				content: textBox.value
			};
			myItem.doRequest("actions/add_item.php", data, parentTodo, function(data){
				console.log("here");
				parentTodo.appendChild(nodeFromHtml("<h3>TODO add to the right place</h3>"));
			});
		};
		let textBox = document.getElementById("addItem_" + parentTodo.getAttribute("data-todoListId")).cloneNode();
		myItem = new editableOnClick(newItem, textBox, updateText);
	});
});
//---------using editableOnClick end

//Remove Todo List Item
let removeListItems = document.getElementsByClassName("removeListItem");
Array.prototype.forEach.call(removeListItems, function (item) {
	item.addEventListener("click", function (e) {
		let parentTodo = findParentByClass(item, "todo");
		let data = {
			itemId: item.getAttribute("data-itemId")
		};
		request("actions/delete_item.php", function (data) {
			console.log(data);
			if (!data.success) {
				addErrorMessage(parentTodo, data.errors);
			}
		}, data, "post");
	});
});
//Handle AJAX for remove, archive and share buttons/actions
//callbacks
let listDeleted = function (parentTodo, actionBtn, data) {
	parentTodo.remove();
};
let listArchived = function (parentTodo, actionBtn, data) {
	parentTodo.remove();
};
let listShared = function (parentTodo, actionBtn, data) {
	parentTodo.remove();
};
//enumeration
let todoListActions = [{
		name: "delete",
		callback: listDeleted
	},
	{
		name: "archive",
		callback: listArchived
	},
	{
		name: "share",
		callback: listShared
	}
];
//creation of listeners
todoListActions.forEach(action => {
	let actionList = document.getElementsByClassName(action.name); //get all the action buttons for every list
	Array.prototype.forEach.call(actionList, function (actionBtn) {
		actionBtn.addEventListener("click", function (e) { //add listener to each action button
			let parentTodo = findParentByClass(actionBtn, "todo"); //get the parent <div class="todo"
			let data = {
				todoListId: parentTodo.getAttribute("data-todoListId")
			}; //ajax post data
			//send ajax request
			request("actions/" + action.name + "_list.php", function (result) {
				console.log(result);
				if (!result.success) { //on errors
					addErrorMessage(parentTodo, result.errors);
				} else {
					action.callback(parentTodo, actionBtn, data); //invoke callback
				}
			}, data, "post");
		});
	});
});