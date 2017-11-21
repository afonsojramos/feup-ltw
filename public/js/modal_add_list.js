let form = document.getElementById("modalAddList");


form.addEventListener("submit", function(){
	var formData = new FormData(form);
	for (let [key, value] of formData.entries()) {
		console.log(key, value);
	  }
	request("actions/add_list.php", function(result){
		console.log(result);
	}, formData, "post");
}, false);