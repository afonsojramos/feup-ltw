'use strict';

function formDataToAjax(formData){
	let data = {};
	for (let [key, value] of formData.entries()) {
		data[key] = value;
	}
	return data;
}

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&');
}

function request(page, onReady, data = {}, type = "get", onError = null, onProgress = null, onAbort = null) {
	type = type.toLowerCase();
	let request = new XMLHttpRequest();
	data.csrf = document.body.getAttribute("data-csrf");
	request.addEventListener("progress", onProgress);
	request.onreadystatechange = function (data) {
		if (this.readyState == 4 && this.status == 200) {
			let result = this.responseText;
			try {
				result = JSON.parse(result);
			} catch (e) {}
			onReady(result);
		}
	};
	request.addEventListener("error", onError);
	request.addEventListener("abort", onAbort);

	if (type == "get") {
		request.open(type, page + "?" + encodeForAjax(data), true);
		request.send();
	} else if(type == "post") {
		request.open(type, page, true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		request.send(encodeForAjax(data));
	}
}