'user strict';

let addErrorModalMessages = function (parent, errors) {
	let errorsDiv = parent.getElementsByClassName("errors")[0];
	errors.innerHTML = "";
	if (errors) { //not undefined
		errors.forEach(error => {
			let span = document.createElement('span');
			span.className += " error center shadow-1";
			span.innerHTML = error;
			errorsDiv.appendChild(span);
		});
	}
};