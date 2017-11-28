let form = document.getElementById("changePassword");

form.addEventListener("submit", function (e) {
	let formData = new FormData(form);
	let data = {};
	for (let [key, value] of formData.entries()) {
		data[key] = value;
	}
	request("actions/edit_profile.php", function (data) {
		console.log(data); //TODO show the list directly
		if(data.success){
			form.style.display = "none";
			form.reset();
		}else{
			addErrorModalMessages(form, data.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);