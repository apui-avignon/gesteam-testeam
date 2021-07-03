var listCourse = document.querySelector('#sort-course');
var latinAlphabet = "AÀÂÄÆaàâäæBbCcDdEÉÈÊËeéèêëFfGgHhIÎÏiîïJjKkLlMmNnOÒÔÖoòôöPpQqRrSsTtUÙÛÜuùûüVvWwXxYyZz";
if (listCourse) {
	var optionsCourse = {
		valueNames:[
			'name',
			'reference',
		],
		alphabet:latinAlphabet
	};
	var courseList = new List('sort-course',optionsCourse);
	courseList.sort('name',{order:"asc"});
}
