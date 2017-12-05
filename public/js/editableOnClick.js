'use strict';
class editableOnClick {
	constructor(element, textBox, updateText, startEmpty = false) {
		this.element = element;
		this.textBox = textBox;
		this.updateText = updateText;
		//create and display the input:text
		this.textBox.edit = true;
		this.element.parentNode.insertBefore(this.textBox, this.element.nextSibling);
		this.textBox.classList.remove("hidden");
		this.element.className += " hidden";
		this.textBox.value = "";
		this.textBox.focus();
		this.textBox.value = startEmpty?"":this.element.innerHTML;
		let self = this;
		//ad event listener for blur on the textbox
		this.textBox.addEventListener("blur", function (a) {
			self.textBox.className += " hidden";
			self.element.classList.remove("hidden");
			if (self.textBox.edit && self.textBox.value.length>0)
				self.updateText(self.textBox);
			self.textBox.remove();
		});
		//add event listener for keydown on the textbox
		this.textBox.addEventListener('keydown', function (e) {
			if (e.keyCode == 13) { //enter
				self.textBox.blur();
			} else if (e.keyCode == 27) { //ESC
				self.textBox.edit = false;
				self.textBox.blur();
			}
		});
	}
	doRequest(url, data, errorContainer, callback) {
		let self = this;
		request(url, function (result) {
			if (callback) {//call callback if it is set
				callback(data, result);
			}
			if (result.success) {
				self.element.innerHTML = self.textBox.value;
			} else {
				addErrorMessage(errorContainer, result.errors);
			}
		}, data, "post");
	}
}