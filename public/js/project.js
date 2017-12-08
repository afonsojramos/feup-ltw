document.addEventListener('keydown', (e) => { //on keyboard press
	if (e.keyCode == 13) { //Enter
		doSearch();
	}
});

document.getElementById("addMember").addEventListener("click", function (e) {
	if (username = prompt("What is the username or email of the new member?")) {
		data = {
			projectId: document.getElementById("projectMainContainer").getAttribute("data-projectId"),
			username: username
		};
		let parentDiv = document.getElementsByClassName("members")[0];
		request("actions/project/add_member.php", function (result) { //send ajax request
			console.log(result);
			if (result.success) { //on errors
				location.reload();
			} else {
				addErrorMessage(parentDiv, result.errors);
			}
		}, data, "post");
	}
});