"use strict";

let modals = document.getElementsByClassName("modal"); //get all modals

for (const modal of modals) {//adds listeners and behaviour for every modal
	let openerId;
	if (openerId = modal.getAttribute("opener")) {
		let btnThatOpens = document.getElementById(modal.getAttribute("opener"));
		let span = modal.getElementsByClassName("closeModal")[0];
		if (btnThatOpens) {
			window.onclick = function (event) {
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
			btnThatOpens.onclick = function (e) {
				modal.style.display = "block";
			}
			if(span != undefined){
				span.onclick = function () {
					modal.style.display = "none";
				}
			}

		}
	}
}

let addErrorModalMessages = function(modal, errors) {
	let modalErrors = modal.getElementsByClassName("modalErrors")[0];
	modalErrors.innerHTML = "";
	if(errors){//not undefined
		errors.forEach(error => {
			let span = document.createElement('span');
			span.className += " errorMessage center shadow-1";
			span.innerHTML = error;
			modalErrors.appendChild(span);
		});
	}
};
/*
Example of modal html

<div class="modal" opener="openModal">
	<div class="modalContent">
		<div class="modalHeader">
			<span class="closeModalCross"><i class="material-icons">close</i></span>
			<h2>Modal Header</h2>
		</div>
		<div class="modalBody">
			<p>Some text in the Modal Body</p>
			<p>Some other text...</p>
		</div>
			<div class="modalFooter">
			<h3>Modal Footer</h3>
			<span class="closeModal">close example</span>
		</div>
	</div>
</div>
<a id="openModal">Open Modal</a>

*/