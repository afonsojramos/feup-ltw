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
				addErrorModalMessages(parentTodo, data.errors);
			}
		}, data, "post");
	});
});
//add a new item to a TodoList
let todoItemLabels = document.getElementsByClassName("todoItemLabel");
Array.prototype.forEach.call(todoItemLabels, function (item) {
	item.addEventListener("click", function (e) { //when a checkbox state changes
		//create and display the input:text
		let textBox = document.getElementById("editItem_" + item.getAttribute("data-itemId")).cloneNode();
		textBox.edit = true;
		item.parentNode.insertBefore(textBox, item.nextSibling);
		textBox.classList.remove("hidden");
		item.className += " hidden";
		textBox.value = "";
		textBox.focus();
		textBox.value = item.innerHTML;
		//ad event listener for blur on the textbox
		textBox.addEventListener("blur", function (a) {
			textBox.className += " hidden";
			item.classList.remove("hidden");
			if (textBox.edit)
				textBox.updateText();
		});
		//add event listener for keydown on the textbox
		textBox.addEventListener('keydown', function (e) {
			if (e.keyCode == 13) { //enter
				textBox.blur();
			} else if (e.keyCode == 27) { //ESC
				textBox.edit = false;
				textBox.blur();
			}
		});
		//ajax request to update the text
		textBox.updateText = function () {
			let parentTodo = findParentByClass(item, "todo"); //get the <div class="todo"> above
			let data = {
				itemId: item.getAttribute("data-itemId"),
				action: "content",				
				content: textBox.value
			};
			request("actions/edit_item.php", function (data) {
				console.log(data);
				if (!data.success) {
					addErrorModalMessages(parentTodo, data.errors);
				}
			}, data, "post");
		};
	});
});
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
				addErrorModalMessages(parentTodo, data.errors);
			}
		}, data, "post");
	});
});
//Remove Todo List
let removeList = document.getElementsByClassName("delete");
Array.prototype.forEach.call(removeList, function (list) {
	list.addEventListener("click", function (e) {
		let parentTodo = findParentByClass(list, "todo"); 
		let data = {
			todoListId: list.getAttribute("data-todoListId")
		};		
		request("actions/delete_list.php", function (data) {
			console.log(data);
			if (!data.success) {
				addErrorModalMessages(parentTodo, data.errors);
			}
		}, data, "post");
	});
});
//Archive Todo List
let archiveList = document.getElementsByClassName("archive");
Array.prototype.forEach.call(archiveList, function (list) {
	list.addEventListener("click", function (e) {
		let parentTodo = findParentByClass(list, "todo"); 
		let data = {
			todoListId: list.getAttribute("data-todoListId")
		};		
		console.log(data);
		
		request("actions/archive_list.php", function (data) {
			console.log(data);
			if (!data.success) {
				addErrorModalMessages(parentTodo, data.errors);
			}
		}, data, "post");
	});
});