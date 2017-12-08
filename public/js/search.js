let search = document.getElementById("search");


function getJsonFromUrl() {
	let query = location.search.substr(1);
	let result = {};
	query.split("&").forEach(function(part) {
	  let item = part.split("=");
	  result[item[0]] = decodeURIComponent(item[1]);
	});
	return result;
}
function populateSearchBar() {
	let js = getJsonFromUrl();
	console.log(js);
	if(js.hasOwnProperty('members')){
		js.members.split(',').forEach(function(element){
			search.value= search.value.concat("@"+element+" ");
		})
	}

	if(js.hasOwnProperty('tags')){
		js.tags.split(',').forEach(function(element){
			search.value= search.value.concat("!"+element+" ");
		})
	}

	if(js.hasOwnProperty('projects')){
		js.projects.split(',').forEach(function(element){
			search.value= search.value.concat("#"+element+" ");
		})
	}

	if(js.hasOwnProperty('words')){
		js.words.split(',').forEach(function(element){
			search.value= search.value.concat(""+element+" ");
		})
	}

	if(js.hasOwnProperty('expressions')){
		js.expressions.split(',').forEach(function(element){
			search.value= search.value.concat("\""+element+"\"");
		})
	}
}

window.onload = populateSearchBar;


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
		const regex = /(?:@(\w+))|(?:!(\w+))|(?:#(\w+))|([a-zA-ZÀ-ÖØ-öø-ÿ]+)|(?:\"(.*?)\")/g;
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
		// build the url

		let url="?";
		if(res.hasOwnProperty(1))
			url=url.concat("&members=").	concat(res[1].join(','));
		if(res.hasOwnProperty(2))
			url=url.concat("&tags=").		concat(res[2].join(','));
		if(res.hasOwnProperty(3))
			url=url.concat("&projects=").	concat(res[3].join(','));
		if(res.hasOwnProperty(4))
			url=url.concat("&words=").		concat(res[4].join(','));
		if(res.hasOwnProperty(5))
			url=url.concat("&expressions=").concat(res[5].join(','));

		document.location.href=window.location.href.replace(window.location.search,'').concat(url);
	}
}