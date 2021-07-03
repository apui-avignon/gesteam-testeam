<div id="graphic">
	<fieldset class="chart">
		<button class="arrow arrow__left<?php if ($first_date_graphic <= $course_parameters['start_date']) { ?> button--deactivated<?php } ?>" type="submit" name="button_inf" id="button_inf" value=<?= $first_date_graphic ?>>
			< </button>

				<div class="ct-chart"></div>

				<button class="arrow arrow__right<?php if (($course_parameters['end_date'] == $last_date_graphic) || $last_date_graphic >= $last_evaluation_date) { ?> button--deactivated <?php } ?>" type="submit" name="button_sup" id="button_sup" value=<?= $last_date_graphic ?>> > </button>

	</fieldset>
	<hr>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/chartist.min.js"></script>
<script>
	(function() {
		var period = 7;
		var columns = 15;
		var imgTwo = '<img src="images/felicitations.svg">';
		var imgOne = '<img src="images/normal.svg">';
		var imgMinusOne = '<img src="images/avertissement.svg">';
		var imgMinusTwo = '<img src="images/alerte.svg">';
		var imgRedCard = '<img src="images/card.svg">';
		var imgDefault = '<img src="images/default.svg">';

		var pourcent = <?= json_encode($array_pourcent); ?>;
		var dates = <?= json_encode($array_dates); ?>;



		var group = <?= json_encode($group) ?>;
		var student = <?= json_encode($student); ?>;
		var courseId = <?= json_encode($course_parameters['id_course']); ?>;
		var redCard = <?= json_encode($array_red_cards); ?>;

		var chart = new Chartist.Line('.ct-chart', {
			labels: <?= $array_weeks; ?>,
			series: [<?= $array_appreciations; ?>]
		}, {
			fullWidth: true,
			chartPadding: {
				top: 40,
				left: 20,
				right: 40
			},
			stretch: true,
			height: '300px',
			axisY: {
				type: Chartist.FixedScaleAxis,
				showLabel: false,
				offset: 0,
				ticks: [-2, -1, 0, 1],
				low: -2,
				high: 1
			},
		});

		function insertSvg(data, e, a, bool) {
			var el = data.element.parent();
			if (!bool) {
				<?php if ($click == true) { ?>
					var e = el.foreignObject(e, {
						width: 26,
						height: 26,
						x: data.x - 20,
						y: data.y - 20,
						class: 'ct-icon'
					});
					e._node.addEventListener('click', function(e) {
						x = data.value.x;
						var dataSeriesBool = data.series[x].bool;
						document.getElementById("graph-details").style.display = "block";
						if (dataSeriesBool == 1) {
							displayDetails(dates[x]);
						}
					});
				<?php } else { ?>
					var e = el.foreignObject(e, {
						width: 26,
						height: 26,
						x: data.x - 20,
						y: data.y - 20,

						class: 'ct-icon  ct-icon--empty'
					});

				<?php } ?>
			} else {
				var e = el.foreignObject(e, {
					width: 26,
					height: 26,
					x: data.x - 20,
					y: data.y - 20,

					class: 'ct-icon ct-icon--empty'
				});
			}
		}


		function insertPourcent(data, i) {
			var el = data.element.parent();
			let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");

			let text = document.createElementNS("http://www.w3.org/2000/svg", "text");
			text.setAttribute('x', '0');
			text.setAttribute('y', '20');
			text.classList.add('ct-label-top');
			text.classList.add('ct-horizontal');
			text.classList.add('ct-end');
			text.textContent = i + '%';


			svg.appendChild(text);

			var e = el.foreignObject(svg, {
				width: 40,
				height: 40,
				x: data.x - 8,
				y: -10, //280

			});

		}


		function insertRedCards(data, i) {
			if ((typeof i !== 'undefined') && (i > 0)) {
				var el = data.element.parent();
				var e = el.foreignObject(imgRedCard, {
					width: 20,
					height: 20,
					x: data.x - 6,
					y: 250, //280
					class: 'ct-icon--card'
				});
			}
		}

		chart.on('draw', function(data) {
			if (data.type === 'point') {
				var ord = data.value.y;
				var abs = data.value.x;
				var icons = document.querySelectorAll('.ct-icon');
				if (ord <= -1.5) {
					insertSvg(data, imgMinusTwo, abs);
				} else if (ord <= -.5) {
					insertSvg(data, imgMinusOne, abs);
				} else if (ord >= .5) {
					insertSvg(data, imgTwo, abs);
				} else {
					var dataSeriesBool = data.series[abs].bool;
					if (dataSeriesBool == 0) {
						insertSvg(data, imgDefault, abs, true);
					} else {
						insertSvg(data, imgOne, abs);
					}
				}
				insertRedCards(data, redCard[abs]);
				<?php if ($click == false) { ?>
					insertPourcent(data, pourcent[abs]);
				<?php } ?>
			}
		});

		var elAll = document.querySelector('#graphic');
		var buttonInf = document.querySelector('#button_inf');
		var buttonSup = document.querySelector('#button_sup');
		if (buttonInf) {
			buttonInf.addEventListener('click', function(e) {
				e.preventDefault();
				var buttonInf = document.querySelector('#button_inf');
				var btnInfVal = buttonInf.value;
				if (btnInfVal) {
					postGraph(btnInfVal, 'left');
				}
			});
		}
		if (buttonSup) {
			buttonSup.addEventListener('click', function(e) {
				e.preventDefault();
				var buttonSup = document.querySelector('#button_sup');
				var btnSupVal = buttonSup.value;
				if (btnSupVal) {
					postGraph(btnSupVal, 'right');
				}
			});
		}


		// AJAX function
		function postGraph(val, direction) {
			$.post(
				'index.php', {
					p: 'history/graphic',
					value: val,
					direction: direction,
					group: group,
					student: student,
					course_id: courseId
				},
				function(data) {
					$("#graphic").html(data);
				},
				'text'
			);
		}

		function displayDetails(val) {
			$.post(
				'index.php', {
					p: 'history/details',
					course_id: courseId,
					group: group,
					student: student,
					date: val,
				},
				function(data) {
					$("#graph-details").html(data);
				},
				'text'
			);
		}

	})();
</script>