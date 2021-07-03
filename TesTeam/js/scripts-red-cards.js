// table red card
var tableRedCards = document.querySelector('#table-red-cards');
if (tableRedCards) {
	var optionsRedCards = {
		valueNames:[
			'link--course',
			'sort--group',
			{ name: 'sort--name', attr: 'data-name' },
			'sort--red',
			'sort--archive',
			'sort--status'
		],
		alphabet:latinAlphabet
	};
	var tableListRedCards = new List('table-red-cards',optionsRedCards);
	tableListRedCards.sort('sort--status',{order:"asc"});
}
