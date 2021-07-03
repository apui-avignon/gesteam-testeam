<div class="body__head">
    <div class="body__intro">
        <h2 class="h1">Modification de cours</h2>
    </div>
</div>
<div class="body__content">
    <div class="size--large">
        <p class="h2"><?= $course_parameters['course']; ?></p>
        <div class="columns">
            <input id="course_name" name="course_name" type="hidden" value="<?= $course_parameters['course']; ?>">
            <input id="course_id" name="course_id" type="hidden" value="<?= $course_parameters['id_course']; ?>">
            <div class="columns__item">
                <label for="start_date">Date de début&nbsp;:</label><br>
                <input class="input size--small" type="date" name="start_date" id="start_date" value="<?= $course_parameters['start_date']; ?>">
            </div>
            <div class="columns__item">
                <label for="end_date">Date de fin&nbsp;:</label><br>
                <input class="input size--small" type="date" name="end_date" id="end_date" value="<?= $course_parameters['end_date']; ?>">
            </div>
        </div>
        <div class="columns">
            <div class="columns__item">
                <label for="limit_yellow_card">Nombre de cartons jaunes <br>pour un rouge&nbsp;:</label><br>
                <input min="0" value="<?= $course_parameters['threshold_red_card']; ?>" class="input size--xsmall" type="number" name="limit_yellow_card" id="limit_yellow_card">
            </div>
            <div class="columns__item">
                <label for="period">Période d’évaluation <br>(en semaines)&nbsp;:</label><br>
                <p>Vous avez défini <br>la période d’évaluation à <br><span class="underline"><?= $course_parameters['period']; ?> semaine(s)</span></p>
            </div>
        </div>
    </div>
    <div class="buttons">
        <button type="submit" id="edit-course" class="button button--action button--primary">Modifier</button>
        <a href="" class="buttons__secondary canceled">Annuler</a>
    </div>
</div>