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
			btnThatOpens.onclick = function () {
				modal.style.display = "block";
				e.stopPropagation();
			}
			span.onclick = function () {
				modal.style.display = "none";
			}
		}
	}
}
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