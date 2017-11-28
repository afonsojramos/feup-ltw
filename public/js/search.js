let search = document.getElementById("search");

function clearSearch(){
	if(search == document.activeElement){
		search.value = "";
		search.blur();
	}
}

function enterSearch(e){
	search.focus();
	e.preventDefault();
}

function doSearch(){
	if(search == document.activeElement){
		//TODO: perform search on search.value
	}
}