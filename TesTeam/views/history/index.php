<div class="body__head">
    <div class="body__intro">
        <h2 class="h1">
            <?= $course_parameters['course']; ?>
        </h2>
        <p>Suivez l’évolution des évaluations
            reçues
            tout au long des semaines</p>
    </div>
    <div class="body__action">
        <button type="submit" class="button button--action button--show">
            Voir le tableau
        </button>
        <span class="hide reference"><?= $course_parameters['id_course'] ?></span>
    </div>
</div>

<div id="display">
    <?php include('display.php'); ?>
</div>
<?php include('select_buttons.php'); ?>

<script>
    document.getElementById("graph-details").style.display = "block";
</script>