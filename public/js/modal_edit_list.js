let editForm = document.getElementById("modalEditList");

editForm.addEventListener("submit", function (e) {
	let parentTodo = findParentByClass(title, "todo");
	let formData = new FormData(editForm);
	addErrorMessage(formData.entries(), data.errors);
	for (let [key, value] of formData.entries()) {
		data[key] = value;
	}
	data[key+1]=parentTodo.getAttribute("data-todoListId");
	request("actions/list/edit_list.php", function (data) {
		if(data.success){
			editForm.style.display = "none";
			editForm.reset();
			displayNewTodoList(data.todoListId);
		}else{
			addErrorMessage(editForm, data.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);