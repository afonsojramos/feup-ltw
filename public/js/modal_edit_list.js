let editForms = document.getElementsByClassName("modalEditList");
Array.prototype.forEach.call(editForms, function(editListForm) {

    editListForm.addEventListener("submit", function(e) {
        let formData = new FormData(editListForm);
        let data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        request("actions/list/edit_list.php", function(result) {
            if (result.success) {
                let todo = document.getElementById("todo_" + data.todoListId);
                let todoTitle = todo.getElementsByClassName("todoTitle")[0];
                todoTitle.innerHTML = data.title;

                /*TODO: change dinamically colour and tags
                let todoColour = todo.getElementsByClassName("todo show-on-hover-parent colour-" + data.todoListId);
                todoColour.innerHTML = data.colour; */

                editListForm.style.display = "none";

                /* 
                displayNewTodoList(data.todoListId);
                parentNode.replaceChild(editListForm, todo); */
            } else {
                addErrorMessage(editListForm, result.errors);
            }
        }, data, "post");
        e.preventDefault();
    }, false);

    let formData = new FormData(editListForm);

});