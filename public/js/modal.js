'use strict';

let modals = document.getElementsByClassName("modal"); //get all modals

for (const modal of modals) {//adds listeners and behaviour for every modal
	let openerId;
	if (openerId = modal.getAttribute("opener")) {
		let btnThatOpens = document.getElementById(modal.getAttribute("opener"));
		let span = modal.getElementsByClassName("closeModal")[0];
		if (btnThatOpens) {
			btnThatOpens.onclick = function () {
				modal.style.display = "block";
			}
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
			span.onclick = function () {
				modal.style.display = "none";
			}
		}
	}
}