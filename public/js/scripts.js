'user strict';

let addErrorMessage = function (parent, errors) {
	let errorsDiv = parent.getElementsByClassName("errors")[0];
	errorsDiv.innerHTML = "";
	if (errors) { //not undefined
		errors.forEach(error => {
			errorsDiv.appendChild(nodeFromHtml('<span class="error center shadow-1">' + error + '</span>'));
		});
		setInterval(function(){
			errorsDiv.innerHTML = "";
		},3500);
	}
};
let addSuccessMessage = function (parent) {
	let errorsDiv = parent.getElementsByClassName("errors")[0];
	errorsDiv.appendChild(nodeFromHtml('<span class="success center shadow-1">success!</span>'));
	setInterval(function(){
		errorsDiv.innerHTML = "";
	},3500);
};

let findParentByClass = function (child, cls) {
	while ((child = child.parentElement) && !child.classList.contains(cls));
	return child;
};

let nodeFromHtml = function (html) {
	var temp = document.createElement('temp');
	temp.innerHTML = html;
	return temp.children[0];
};