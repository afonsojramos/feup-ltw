//edit password ajax
let form = document.getElementById("changePassword");

form.addEventListener("submit", function (e) {
	let formData = new FormData(form);
	data = formDataToAjax(formData);
	request("actions/user/edit_profile.php", function (result) {
		if(result.success){
			addSuccessMessage(form);
			form.reset();
		}else{
			addErrorMessage(form, result.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);

//edit profile information
let form2 = document.getElementById("editProfile");

form2.addEventListener("submit", function (e) {
	let formData = new FormData(form2);
	data = formDataToAjax(formData);
	request("actions/user/edit_profile.php", function (result) {
		if(result.success){
			addSuccessMessage(form2);
		}else{
			addErrorMessage(form2, result.errors);
		}
	}, data, "post");
	e.preventDefault();
}, false);


document.getElementById("deleteUserAccount").addEventListener("click", function(e){
	if (confirm("Do you really want to delete your account") && confirm("This operation is irreversible, proceed anyway (double checking)?") ) {
		request("actions/user/delete_profile.php", function (result) {
			console.log(result);
			if(result.success){
				alert("Sorry to see you go :'(");
				location.reload();
			}else{
				alert("Unable to delete account: " + result.errors.join());
			}
		}, {}, "post");
	}
});