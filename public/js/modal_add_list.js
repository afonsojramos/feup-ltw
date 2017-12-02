let form = document.getElementById("modalAddList");

form.addEventListener("submit", function (e) {
	let formData = new FormData(form);
	let data = {};
	for (let [key, value] of formData.entries()) {
		data[key] = value;
	}
	request("actions/add_list.php", function (data) {
		if(data.success){
			form.style.display = "none";
			form.reset();
			displayNewTodoList(data.todoListId);
		}else{
			addErrorMessage(form, data.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);

function displayNewTodoList(id){
	request("templates/dashboard/todo.php", function (html) {
		document.getElementsByClassName("todos")[0].appendChild(nodeFromHtml(html));
	}, {todoListId: id});
}