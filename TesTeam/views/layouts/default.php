<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Évaluation de groupe - Tableau de bord</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Baloo%7CMontserrat:400,700&display=swap" />
	<link rel="stylesheet" href="css/style.css" />
	<link id="favicon" rel="icon" type="image/png" href="images/card--red.png" />
	<script>
		function dynamicFavicon(n) {
			if (n > 9) {
				n = "9+";
			}
			var svgNs = 'http://www.w3.org/2000/svg';
			var thisSvg = document.createElementNS(svgNs, 'svg');
			var thisPath = document.createElementNS(svgNs, 'path');
			var link = document.getElementById('favicon');
			thisSvg.setAttributeNS(null, 'viewBox', '0 0 200 200');
			if (n == 0) {
				thisPath.setAttributeNS(null, 'fill', '#999');
			} else {
				thisPath.setAttributeNS(null, 'fill', '#e63c63');
			}
			thisPath.setAttributeNS(null, 'd', 'M20.72 30.35L133.83.05l45.45 169.61L66.17 200z');
			thisSvg.appendChild(thisPath);
			if (n || n == 0) {
				let thisText = document.createElementNS(svgNs, 'text');
				thisText.setAttributeNS(null, 'x', '105');
				thisText.setAttributeNS(null, 'y', '120');
				thisText.setAttributeNS(null, 'fill', '#fff');
				thisText.setAttributeNS(null, 'font-size', '8rem');
				thisText.setAttributeNS(null, 'font-family', 'Arial');
				thisText.setAttributeNS(null, 'font-weight', 'Bold');
				thisText.setAttributeNS(null, 'dominant-baseline', 'middle');
				thisText.setAttributeNS(null, 'text-anchor', 'middle');
				thisText.textContent = n;
				thisSvg.appendChild(thisText);
			}
			var serializer = new XMLSerializer();
			var thisSrc = encodeURIComponent(serializer.serializeToString(thisSvg));
			thisSrc = "data:image/svg+xml," + thisSrc;
			var thisImg = document.createElement("img");
			thisImg.setAttribute('src', thisSrc);
			link.setAttribute('href', thisSrc);
			link.setAttribute('type', "image/svg+xml");
		}
	</script>
</head>

<body class="app--large">
	<div class="loading">
		<div class="loading__inner">Chargement&hellip;</div>
	</div>
	<?= $content ?>
	<script src="js/allscripts.js"></script>
	<div class="help hide">
		<div class="help__inner">
			<p class="h1">Aide</p>
			<p class="help__text">Souhaitez-vous une aide à l'utilisation de cette application&nbsp;?</p>
			<p class="buttons buttons--action"><a href="pdf/tutoriel-enseignant.pdf" target="_blank" class="button button--action button--primary help__yes">Oui, montrez-moi</a><a href="#" class="button button--action button--secondary buttons__secondary help__no">Non merci</a></p>
			<p>Quoi qu'il en soit, cette aide sera toujours accessible via le lien "Aide" en bas de l'application</p>
		</div>
	</div>
	<div class="infos">
		<a href="pdf/tutoriel-enseignant.pdf" target="_blank">Aide</a> | <a href="mentions-legales.html" target="_blank">Mentions légales</a> | <a href="helper/deconnexion.php" id='deconnexion'>Se déconnecter</a>
	</div>
	<footer class="footer"></footer>
	<script>
		const $connect = <?= (isset($_SESSION['connected'])) ? 1 : 0 ?>;
	</script>

	<script src="js/ajax-functions.js"></script>

</body>

</html>