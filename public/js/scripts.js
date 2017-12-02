'user strict';

let addErrorMessage = function (parent, errors) {
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
let addSuccessMessage = function (parent) {
	let errorsDiv = parent.getElementsByClassName("errors")[0];
	errorsDiv.innerHTML = '<span class="success center shadow-1">success!</span>';
};

let findParentByClass = function(child, cls) {
	while ((child = child.parentElement) && !child.classList.contains(cls));
	return child;
};

let nodeFromHtml = function(html) {
	var temp = document.createElement('temp');
	temp.innerHTML = html;
	return temp.children[0];
};