"use strict";

function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&');
}

function request(page, onReady, data = {}, type = "get", onError = null, onProgress = null, onAbort = null){
	let request = new XMLHttpRequest();

	request.addEventListener("progress", onProgress);
	request.addEventListener("load", onReady);
	request.addEventListener("error", onError);
	request.addEventListener("abort", onAbort);

	request.open(type, page, true); //async
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(encodeForAjax({id: 1, name: 'John'}));
}