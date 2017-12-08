let modalEditAddListener = function (editListForm) {
	editListForm.addEventListener("submit", function (e) {
		let formData = new FormData(editListForm);
		let data = {};
		for (let [key, value] of formData.entries()) {
			data[key] = value;
		}
		request("actions/list/edit_list.php", function (result) {
			if (result.success) {
				let todo = document.getElementById("todo_" + data.todoListId);

				let todoTitle = todo.getElementsByClassName("todoTitle")[0];
				todoTitle.innerHTML = data.title;

				let todoTags = todo.getElementsByClassName("tags")[0];

				data.tags = data.tags.split(",").map(function (tag) {
					return `<a href="dashboard.php?tag=${tag}" class="tag"> ${tag}</a>`;
				});
				todoTags.innerHTML = data.tags.join("");

				todo.className = ("todo show-on-hover-parent colour-" + data.colour);

				editListForm.style.display = "none";
			} else {
				addErrorMessage(editListForm, result.errors);
			}
		}, data, "post");
		e.preventDefault();
	}, false);
};

let editForms = document.getElementsByClassName("modalEditList");
Array.prototype.forEach.call(editForms, modalEditAddListener);