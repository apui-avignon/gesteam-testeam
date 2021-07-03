<div class="body__head">
    <div class="body__intro">
        <h2 class="h1">Suppression de cours</h2>
    </div>
</div>
<div class="body__content">
    <div class="size--large">
        <p class="h2">Êtes-vous sûr de vouloir supprimer ce cours&nbsp;?</p>
        <p class="p--big">Nom du cours&nbsp;: <span class="underline underline--gray"><?= $course_parameters['course']; ?></span></p>
    </div>
    <div class="buttons">
        <input id="course_id" name="course_id" type="hidden" value="<?= $course_parameters['id_course']; ?>">
        <button type="submit" id="delete-course" name="delete-course" class="button button--action button--primary" value="<?= $course_parameters['id_course']; ?>" data-id="<?= $course_parameters['course']; ?>">Supprimer</button>
        <a href="" class="buttons__secondary canceled">Annuler</a>
    </div>
</div>