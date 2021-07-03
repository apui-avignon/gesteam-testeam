<!DOCTYPE html>
<html lang="fr">
<?php $configs = include('config.php'); ?>
<head>
	<meta charset="utf-8" />
	<title>Évaluation de groupe</title>
	<link href="https://fonts.googleapis.com/css?family=Baloo%7CMontserrat:400,700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="../<?= $configs['teacher_app_name'] ?>/css/style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body class="page">
	<?= $content ?>
	<div class="help hide">
		<div class="help__inner">
				<p class="h1">Aide</p>
				<p class="help__p help__text">Souhaitez-vous une aide à l'utilisation de cette application&nbsp;?</p>
				<p class="buttons buttons--action"><a href="../<?= $configs['teacher_app_name'] ?>/aide.html" target="_blank" class="button button--action button--primary help__yes">Oui, montrez-moi</a><a href="#" class="button button--action button--secondary buttons__secondary help__no">Non merci</a></p>
				<p class="help__p">Quoi qu'il en soit, cette aide sera toujours accessible via le lien "Aide" en bas de l'application</p>
			</div>
	</div>
	<div class="infos infos--student">
		<a href="../<?= $configs['teacher_app_name'] ?>/aide.html" target="_blank">Aide</a> | <a href="../<?= $configs['teacher_app_name'] ?>/mentions-legales.html" target="_blank">Mentions légales</a> | <a href="helper/deconnexion.php" id='deconnexion'>Se déconnecter</a>
	</div>
	<script src="../<?= $configs['teacher_app_name'] ?>/js/custom-select.min.js"></script>
	<script src="../<?= $configs['teacher_app_name'] ?>/js/popper.js"></script>
	<script src="../<?= $configs['teacher_app_name'] ?>/js/tippy.js"></script>
	<script src="../<?= $configs['teacher_app_name'] ?>/js/chartist.min.js"></script>
	<script>
		// custom select
		const header = document.querySelector('.header');
		const $help = document.querySelector('.help');
		const $helpYes = document.querySelector('.help__yes');
		const $helpNo = document.querySelector('.help__no');
		if (header) {
			if (!localStorage.getItem('isHelpStudent')) {
				$help.classList.remove('hide');
			}
			$helpYes.addEventListener('click', function(event) {
				localStorage.setItem('isHelpStudent', true);
				$help.classList.add('hide');
			});
			$helpNo.addEventListener('click', function(event) {
				localStorage.setItem('isHelpStudent', true);
				$help.classList.add('hide');
			});
		}
		const elSelect = document.querySelector('select');
		if (elSelect) {
			customSelect('select');
		}
		// tooltip
		tippy('.alerts__title', {
			animation: 'fade',
			arrow: true,
			interactive: true,
			trigger: 'click',
			aria: null,
			onMount({
				reference
			}) {
				reference.setAttribute('aria-expanded', 'true')
			},
			onHide({
				reference
			}) {
				reference.setAttribute('aria-expanded', 'false')
			},
		});
	</script>
	<script src="js/ajax-functions.js"></script>

</body>

</html>