<div class="dashboard__body">
    <div class="body__head">
        <div class="body__intro">
            <h2 class="h1">Ajout de cours</h2>
        </div>
    </div>
    <div class="body__content">
        <div class="size--large">
            <form method="POST">
                <select name="selected-course" id="selected-course" size="1" onchange="activate();">
                    <option value="null" selected>Choisissez un cours...</option>
                    <?php
                    foreach ($teacher_s_courses_moodle as $teacher_s_course_moodle) :
                        $course_exist = false;
                        foreach ($teacher_s_courses as $teacher_s_course) :
                            if ($teacher_s_course_moodle['id'] == $teacher_s_course['id_course']) :
                                $course_exist = true;
                                break;
                            endif;
                        endforeach;
                        if ($course_exist == false) :
                            $course_name = $teacher_s_course_moodle['fullname'];
                    ?>
                            <option data-id="<?= $teacher_s_course_moodle['id'] ?>">
                                <?= $course_name ?>
                            </option>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </select>
                <div class="columns">
                    <div class="columns__item">
                        <label for="dateStart">Date de début&nbsp;:</label><br>
                        <input class="input size--small" type="date" name="start_date" id="start_date" value="">
                    </div>
                    <div class="columns__item">
                        <label for="dateEnd">Date de fin&nbsp;:</label><br>
                        <input class="input size--small" type="date" name="end_date" id="end_date" value="">
                    </div>
                </div>
                <div class="columns">
                    <div class="columns__item">
                        <label for="limit_yellow_card">Nombre de cartons jaunes <br>pour un rouge&nbsp;:</label><br>
                        <input min="1" value="5" class="input size--xsmall" type="number" name="limit_yellow_card" id="limit_yellow_card" value="">
                    </div>
                    <div class="columns__item">
                        <label for="evaluation_period">Période d’évaluation <br>(en semaines)&nbsp;:</label><br>
                        <input size="2" min="1" value="1" class="input size--xsmall" type="number" name="evaluation_period" id="evaluation_period" value="">
                    </div>
                </div>
            </form>
        </div>
        <div class="buttons">
            <button type="button" class="button button--action button--primary" id="add-course" name="add-course" disabled>Ajouter</button>
            <a href="" class="buttons__secondary canceled">Annuler</a>
        </div>
    </div>
</div>

<script type="text/javascript">
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var dd = String(today.getDate()).padStart(2, '0');
    if (mm >= 1 && mm <= 6) {
        document.getElementById('start_date').valueAsDate = new Date(yyyy + "-" + mm + "-" + dd);
        document.getElementById('end_date').valueAsDate = new Date(yyyy + "-07-01");
    } else if (mm > 6 && mm <= 12) {
        document.getElementById('start_date').valueAsDate = new Date(yyyy +"-" + mm + "-" + dd);
        yyyy = yyyy + 1;
        document.getElementById('end_date').valueAsDate = new Date(yyyy + "-01-31");
    }

    function activate() {
        var select = document.getElementById("selected-course");
        var choice = select.selectedIndex;
        var choice_value = select.options[choice].value;
        if (choice_value !== 'null') {
            document.getElementById('add-course').disabled = false;
        } else {
            document.getElementById('add-course').disabled = true;
        }
    }

</script>