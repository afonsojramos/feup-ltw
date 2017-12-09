let form = document.getElementById("modalAddList");

form.addEventListener("submit", function (e) {
	let formData = new FormData(form);
	let data = {};
	for (let [key, value] of formData.entries()) {
		data[key] = value;
	}
	request("actions/list/add_list.php", function (data) {
		if(data.success){
			form.style.display = "none";
			form.reset();
			displayNewTodoList(data.todoListId);
			let noTodos = document.getElementById("noTodos");
			if(noTodos){
				noTodos.remove();
			}
		}else{
			addErrorMessage(form, data.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);
