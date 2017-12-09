let loginForm = document.querySelector("#loginForm");
loginForm.addEventListener("submit", function (e) {
	let formData = new FormData(loginForm);
	data = formDataToAjax(formData);
	request("actions/user/login.php", function (result) {
		if (result.success) {
			window.location.replace("dashboard.php");
		} else {
			addErrorMessage(loginForm, result.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);

let usernameField = document.getElementById("username");
if (usernameField.value == "") {
	usernameField.focus();
}else{
	let passwordField = document.getElementById("password");
	passwordField.focus();
}