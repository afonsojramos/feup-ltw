document.addEventListener('keydown', (e) => { //on keyboard press
	if (e.ctrlKey && e.keyCode == 65) { //Ctrl+a -> open search
		document.getElementById("addMember").click();
		e.preventDefault();
	}
});


let parentDiv = document.getElementsByClassName("members")[0];
//add member
document.getElementById("addMember").addEventListener("click", function (e) {
	if (username = prompt("What is the username or email of the new member?")) {
		data = {
			projectId: document.getElementById("projectMainContainer").getAttribute("data-projectId"),
			username: username
		};
		request("actions/project/add_member.php", function (result) { //send ajax request
			if (result.success) { //on errors
				location.reload();
			} else {
				addErrorMessage(parentDiv, result.errors);
			}
		}, data, "post");
	}
});

//remove member
let removeMemberLinks = document.getElementsByClassName("removeMember");
Array.prototype.forEach.call(removeMemberLinks, function (removeMemberLink) {
	removeMemberLink.addEventListener("click", function (e) {
		let data = {
			projectId: document.getElementById("projectMainContainer").getAttribute("data-projectId"),
			userId: removeMemberLink.getAttribute("data-userId")
		};
		request("actions/project/delete_member.php", function (result) {
			if (result.success) {
				findParentByClass(removeMemberLink, "memberContainer").remove();
			} else {
				addErrorMessage(parentDiv, result.errors);
			}
		}, data, "post");
		e.preventDefault();
	}, false);

	let formData = new FormData(removeMemberLink);
});

//delete project
document.getElementById("deleteProject").addEventListener("click", function (e) {
	if (confirm("Are you sure you want to delete this project?")) {
		data = {
			projectId: document.getElementById("projectMainContainer").getAttribute("data-projectId")
		};
		request("actions/project/delete_project.php", function (result) { //send ajax request
			console.log(result);
			if (result.success) { //on errors
				window.location.replace("dashboard.php");
			} else {
				addErrorMessage(parentDiv, result.errors);
			}
		}, data, "post");
	}
});