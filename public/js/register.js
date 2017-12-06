let registerForm = document.getElementById("registerForm");
registerForm.addEventListener("submit", function (e) {
	let formData = new FormData(registerForm);
	data = formDataToAjax(formData);
	request("actions/user/register.php", function (result) {
		if (result.success) {
			window.location.replace("login.php?username=" + document.getElementById("username").value);
		} else {
			addErrorMessage(registerForm, result.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);