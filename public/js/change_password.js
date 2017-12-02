let form = document.getElementById("changePassword");

form.addEventListener("submit", function (e) {
	let formData = new FormData(form);
	data = formDataToAjax(formData);
	request("actions/edit_profile.php", function (result) {
		if(result.success){
			addSuccessMessage(form);
			form.reset();
		}else{
			addErrorMessage(form, result.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);