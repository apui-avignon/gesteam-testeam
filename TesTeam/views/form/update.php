<div class="body__head">
    <div class="body__intro">
        <h2 class="h1">Mise à jour des groupes du cours</h2>
    </div>
</div>
<div class="body__content">
    <div class="size--large">
        <p class="p--big">Il est important de mettre à jour régulièrement les groupes sur l'application. Cela permet d'ajouter les étudiants qui n'etaient pas encore inscrit lors de la création de votre cours ou simplement à mettre à jour les groupes de travail dèjà existants. </p> </br>
        <p class="h2">Êtes-vous sûr de vouloir mettre à jour les groupes de ce cours&nbsp;?</p>
        <p class="p--big">Nom du cours&nbsp;: <span class="underline underline--gray"><?= $course_parameters['course']; ?></span></p>
        <p><b>Attention : lorsque vous mettez à jour, les résultats de la dernière semaine d'évaluation seront eronnés.</b></p>
    </div>
    <div class="buttons">

        <button type="submit" id="update-course" value="<?= $course_parameters['id_course']; ?>" data-id="<?= $course_parameters['course']; ?>" class="button button--action button--primary"> Mettre à jour les groupes </button>
        <a href="" class="buttons__secondary canceled">Annuler</a>
    </div>
</div>