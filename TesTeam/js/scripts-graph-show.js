var elSelect = document.querySelectorAll('select');
var selectEtudiant = document.getElementById('student');
if (elSelect) {
    for (var i = 0; i < elSelect.length; i++) {
        customSelect(elSelect[i]);
	}
}
if (elSelect[0]) {
    var cstSelCurrent = elSelect[0].customSelect;
    var cstSelNext = elSelect[1].customSelect;
    cstSelCurrent.select.addEventListener('change', (e) => {
    	var selectGroupeValue = cstSelCurrent.select.value;
    	elSelect[1].customSelect.empty();
    	populateEtudiant(selectGroupeValue);
    });
}
function insertOption(text,value){
	var option = document.createElement('option');
	option.text = text;
	option.value = value;
	elSelect[1].customSelect.append(option);
}
function populateEtudiant(v) {
	insertOption('Tous les étudiants','');
    for (var i = 0; i < scriptGraphShowData.length; i++) {
        if (v == '' || v == scriptGraphShowData[i]['id_group']) {
            insertOption((scriptGraphShowData[i]['firstname']+" "+scriptGraphShowData[i]['lastname']),scriptGraphShowData[i]['username']);
        }
    }
}
