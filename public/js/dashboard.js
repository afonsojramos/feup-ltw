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
	} else if (e.ctrlKey && e.keyCode == 76) { //Ctrl+l -> open modal (new list)
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
//callback for checkbox listener - ITEM
let itemChangeCompleted = function (checkbox) { //when a checkbox state changes
	checkbox.addEventListener("change", function (e) {
		let parentTodo = findParentByClass(checkbox, "todo"); //get the <div class="todo"> above
		let parentItem = findParentByClass(checkbox, "todoListItem");
		let data = {
			itemId: parentItem.getAttribute("data-itemId"),
			completed: checkbox.checked,
			action: "completed"
		};
		request("actions/item/edit_item.php", function (data) {
			if (!data.success) {
				addErrorMessage(parentTodo, data.errors);
			}
		}, data, "post");
	});
};
//callback for text edited listener - ITEM
let itemChangeContent = function (item) {
	item.addEventListener("click", function (e) {
		let myItem, parentTodo = findParentByClass(item, "todo"); //get the <div class="todo"> above
		let parentItem = findParentByClass(item, "todoListItem");
		let updateText = function (textBox) {
			let data = {
				itemId: parentItem.getAttribute("data-itemId"),
				action: "content",
				content: textBox.value
			};
			myItem.doRequest("actions/item/edit_item.php", data, parentTodo);
		};
		let textBox = document.getElementById("editItem_" + parentItem.getAttribute("data-itemId")).cloneNode();
		myItem = new editableOnClick(item, textBox, updateText);
	});
};
//callback for delete item listener - ITEM
let itemDeleteItem = function (item) {
	item.addEventListener("click", function (e) {
		let parentTodo = findParentByClass(item, "todo");
		let parentItem = findParentByClass(item, "todoListItem");
		let data = {
			itemId: parentItem.getAttribute("data-itemId")
		};
		request("actions/item/delete_item.php", function (data) {
			if (data.success) {
				parentItem.remove();
			} else {
				addErrorMessage(parentTodo, data.errors);
			}
		}, data, "post");
	});
};
//callback for edit todolist title - TODOLIST
let listEditTitle = function (title) {
	title.addEventListener("click", function (e) { //when a checkbox state changes
		let myItem, parentTodo = findParentByClass(title, "todo"); //get the <div class="todo"> above
		let updateText = function (textBox) {
			let data = {
				todoListId: parentTodo.getAttribute("data-todoListId"),
				title: textBox.value
			};
			myItem.doRequest("actions/list/edit_list.php", data, parentTodo);
		};
		let textBox = document.getElementById("editTitle_" + parentTodo.getAttribute("data-todoListId")).cloneNode();
		myItem = new editableOnClick(title, textBox, updateText);
	});
};
//callback for add a new item to a todolist - TODOLIST
let listAddItem = function (newItem) {
	newItem.addEventListener("click", function (e) { //when a checkbox state changes
		let myItem, parentTodo = findParentByClass(newItem, "todo"); //get the <div class="todo"> above
		let updateText = function (textBox) {
			let data = {
				todoListId: parentTodo.getAttribute("data-todoListId"),
				content: textBox.value
			};
			let itemsList = parentTodo.getElementsByClassName("items")[0];
			request("actions/item/add_item.php", function (result) {
				console.log(result);
				if (result.success) {
					displayNewTodoListItem(itemsList, result.itemId);
				} else {
					addErrorMessage(parentTodo, result.errors);
				}
			}, data, "post");
		};
		let textBox = document.getElementById("addItem_" + parentTodo.getAttribute("data-todoListId")).cloneNode();
		myItem = new editableOnClick(newItem, textBox, updateText, true);
	});
};

//Handle AJAX for remove, archive and share buttons/actions
//callbacks
let listDeleted = function (parentTodo, actionBtn, data) {
	parentTodo.remove();
};
let listArchived = function (parentTodo, actionBtn, data) {
	parentTodo.remove(); //TODO: replace with correct
};
let listShared = function (parentTodo, actionBtn, data) {
	parentTodo.remove(); //TODO: replace with correct
};
let simpleRequestListener = function (actionBtn, action) {
	actionBtn.addEventListener("click", function (e) { //add listener to each action button
		let parentTodo = findParentByClass(actionBtn, "todo"); //get the parent <div class="todo"
		let data = {
			todoListId: parentTodo.getAttribute("data-todoListId")
		}; //ajax post data
		request("actions/list/" + action.class + "_list.php", function (result) { //send ajax request
			if (!result.success) { //on errors
				addErrorMessage(parentTodo, result.errors);
			} else {
				action.onSuccess(parentTodo, actionBtn, data); //invoke callback
			}
		}, data, "post");
	});
};

//--------------------------Set listeners for initial elements
let baseListners = [{
		class: "todoItem", //change checked state of an item
		callback: itemChangeCompleted
	}, {
		class: "todoTitle", //edit the TodoLists' title
		callback: listEditTitle
	}, {
		class: "addItemText", //add a new item to a TodoList
		callback: listAddItem
	}, {
		class: "todoItemLabel", //edit an item of a TodoList
		callback: itemChangeContent
	}, {
		class: "removeListItem", //Remove Todo List Item
		callback: itemDeleteItem
	}, {
		class: "delete",
		callback: simpleRequestListener,
		onSuccess: listDeleted
	},
	{
		class: "archive",
		callback: simpleRequestListener,
		onSuccess: listArchived
	},
	{
		class: "share",
		callback: simpleRequestListener,
		onSuccess: listShared
	}
];
baseListners.forEach(listener => {
	let elements = document.getElementsByClassName(listener.class);
	Array.prototype.forEach.call(elements, function (element) {
		listener.callback(element, listener);
	});
});

//ajax for html parts
function displayNewTodoList(id) { //get the html for a todolist
	request("templates/dashboard/todo.php", function (html) {
		let newList = nodeFromHtml(html);
		document.getElementsByClassName("todos")[0].appendChild(newList);
		baseListners.forEach(listener => { //set the new listeners
			let toListen = newList.getElementsByClassName(listener.class)[0];
			if (toListen)
				listener.callback(toListen, listener);
		});
	}, {
		todoListId: id
	});
}

function displayNewTodoListItem(parent, id) { //get the html for a todo item
	request("templates/dashboard/item.php", function (html) {
		let newItem = nodeFromHtml(html);
		parent.appendChild(newItem);
		baseListners.forEach(listener => { //set the new listeners
			let toListen = newItem.getElementsByClassName(listener.class)[0];
			if (toListen)
				listener.callback(toListen);
		});
	}, {
		itemId: id
	});
}