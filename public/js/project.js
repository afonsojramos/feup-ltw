document.addEventListener('keydown', (e) => { //on keyboard press
	if (e.keyCode == 13) { //Enter
		doSearch();
	}
});

document.getElementById("addMember").addEventListener("click", function(e) {
	if (username = prompt("What is the username or email of the new member?")) {
		console.log(username);
	}
});