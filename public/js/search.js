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
		const regex = /(?:@(\w+))|(?:!(\w+))|(?:#(\w+))|([a-zA-ZÀ-ÖØ-öø-ÿ]+)|(?:\"(.*)\")/g;
		let m;
		let res={};
		while ((m = regex.exec(search.value)) !== null) {
			// This is necessary to avoid infinite loops with zero-width matches
			if (m.index === regex.lastIndex) {
				regex.lastIndex++;
			}

			// The result can be accessed through the `m`-variable.
			m.forEach((match, groupIndex) => {
				if(match!=undefined && groupIndex!=0){
					set = 0;
					console.log(`Found match, group ${groupIndex}: ${match}`);
					if(!res.hasOwnProperty(groupIndex)){
						res[groupIndex] = [match];
					}else{
						res[groupIndex].push(match)
					}
				}

			});
		}
		request("actions/user/search.php", function (result) {
			/* treat result here */
		}, res, "post");

	}
}