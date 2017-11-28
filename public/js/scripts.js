'user strict';

let addErrorModalMessages = function (parent, errors) {
	let errorsDiv = parent.getElementsByClassName("errors")[0];
	errorsDiv.innerHTML = "";
	if (errors) { //not undefined
		errors.forEach(error => {
			let span = document.createElement('span');
			span.className += " error center shadow-1";
			span.innerHTML = error;
			errorsDiv.appendChild(span);
		});
	}
};

function findParentByClass(child, cls) {
	while ((child = child.parentElement) && !child.classList.contains(cls));
	return child;
}