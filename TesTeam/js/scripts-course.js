// table course
var tableCourse = document.querySelector('#table-course');
if (tableCourse) {
	var tableCourseTh = tableCourse.querySelectorAll('th');
	var tableCourseThGroup = tableCourse.querySelector('#sort--group');
	if (tableCourse) {
		var optionsCourse = {
			valueNames:[
				'sort--group',
				{ name: 'sort--name', attr: 'data-name' },
				'sort--archive',
				'sort--red',
				'sort--0',
				'sort--1',
				'sort--2',
				'sort--3'
			],
			alphabet:latinAlphabet
		};
		var tableListCourse = new List('table-course',optionsCourse);
		tableListCourse.sort('sort--group',{order:"asc"});
		tableCourse.classList.add('grouped');
		tableCourseTh.forEach(function(el, index, array){
			el.addEventListener('click', function(e){
				tableCourse.classList.remove('grouped');
			});
		});
		tableCourseThGroup.addEventListener('click', function(e){
			tableCourse.classList.add('grouped');
		});
	}
}
// tooltip
tippy('.eval--default .alerts__button, .eval--red .bar__button',{
	animation: 'fade',
	arrow: true,
	interactive: true,
	aria: null,
	appendTo: 'parent',
	onMount({ reference }) {
		reference.setAttribute('aria-expanded', 'true')
	},
	onHide({ reference }) {
		reference.setAttribute('aria-expanded', 'false')
	},
});
