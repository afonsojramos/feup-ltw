"use strict";

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&');
}

function request(page, onReady, data = {}, type = "get", onError = null, onProgress = null, onAbort = null) {
	let request = new XMLHttpRequest();

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

	request.open(type, page, true); //async
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax(data));
}