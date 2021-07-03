<div id="sort-course">
	<ul class="menu__list list">
		<?php
		foreach ($teacher_s_courses as $teacher_s_course) :
		?>
			<li class="list__item group">
				<button class="button group__item group__item--grow button--show name" ><?= $teacher_s_course['course'] ?></button>
				<span class="hide reference"><?= $teacher_s_course['id_course'] ?></span>
				<button class="button group__item button--update"><span class="replace button__icon icon--update">Mettre à jour</span></button>
				<button class="button group__item button--edit"><span class="replace button__icon icon--edit">Modifier</span></button>
				<a class="button group__item button--delete" href=""><span class="replace button__icon icon--delete">Supprimer</span></a>
			</li>
		<?php
		endforeach;
		?>
		<li class="list__item group" id="list__empty">
			<button class="button group__item group__item--grow button--show name" >Cours</button>
			<span class="hide reference">000</span>
			<button class="button group__item button--update"><span class="replace button__icon icon--update">Mettre à jour</span></button>
			<button class="button group__item button--edit"><span class="replace button__icon icon--edit">Modifier</span></button>
			<a class="button group__item button--delete" href=""><span class="replace button__icon icon--delete">Supprimer</span></a>
		</li>
	</ul>
	<div class="list__item list__item--center">
		<button class="button button--round button--primary button--action button--add" value="false"><span class="replace button__icon icon--add">
				Ajouter</span>
		</button>
	</div>
</div>
<script src="js/list.min.js"></script>
