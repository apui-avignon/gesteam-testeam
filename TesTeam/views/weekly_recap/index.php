<div class="body__head">
    <div class="body__intro">
        <h2 class="h1">
            <?= $course_parameters['course']; ?>
        </h2>
        <p> Lien à donner à vos étudiants pour qu'ils puissent utiliser l'application : <br>

            <span id="tocopy"><?= $configs['server_name'] ?>/<?= $configs['student_app_name'] ?>/index.php?course_id=<?= $course_parameters['id_course']; ?></span><input type="button" value="Copier" class="js-copy input button--small--margin" data-target="#tocopy"><span id="copyAnswer"></span>
        </p>
        </button>
        <br>
        <p>Envoyer un rappel automatique aux étudiants par mails :</p>
        <div class="jauge-go">
            <div class="gauge">
                <div class="gauge__text"> <?= count($current_votes) . ' étudiant(s) sur ' . count($course_groups) . ' ont voté'; ?></div>
                <div class="gauge__progress"></div>
            </div>
            <div class="go"> <button type="submit" id="send-mail" data-id="<?= $course_parameters['id_course'] ?>" class="input button--small button--small--margin"> GO </button> </div>
        </div>

        <div class="body__action">
        </div>
        <br> <br> <br>
        <p>Retrouvez ici la liste des groupes et des élèves associés, avec leur évaluation. Vous pouvez trier cette liste par groupe, par nom d’élèves, par évaluation.</p>
    </div>
    <div class="body__action">
        <button type="submit" id="history" class="button button--action">
            Voir l'historique
        </button>
        <span class="hide reference"><?= $course_parameters['id_course'] ?></span>

    </div>
</div>

<div class="body__content">

    <table id="table-course">

        <thead>
            <tr>
                <th class="sort" data-sort="sort--group" id="sort--group">Groupe</th>
                <th class="sort col--large" data-sort="sort--name">Nom</th>
                <th class="sort" data-sort="sort--archive">Archive</th>
                <th class="sort" data-sort="sort--red">Carton</th>
                <th class="sort" data-sort="sort--0">M&eacute;diocre</th>
                <th class="sort" data-sort="sort--1">Passable</th>
                <th class="sort" data-sort="sort--2">Bien</th>
                <th class="sort" data-sort="sort--3">Tr&egrave;s&nbsp;bien</th>
            </tr>
        </thead>

        <tbody class="list">
            <?php
            array_push($course_appreciations, array('evaluated_student' => "", 'id_group' => "", 'value' => "", 'COUNT(value)' => ""));
            $group = "";
            $trClasse = "";

            // FOR ALL STUDENT IN THIS COURSE
            foreach ($course_groups_more_informations as $course_group_more_informations) :
                $appreciations = array(0, 0, 0, 0);

                if ($group != $course_group_more_informations['id_group']) {
                    if ($trClasse == "grouped__odd") {
                        $trClasse = "grouped__even";
                    } else {
                        $trClasse = "grouped__odd";
                    }
                    $group = $course_group_more_informations['id_group'];
                }

                // APPRECIATION
                $usernames = array_column($course_appreciations, 'evaluated_student');
                $keys = array_keys($usernames, $course_group_more_informations['username']);
                foreach ($keys as $key) :
                    $appreciations[$course_appreciations[$key]['value'] + 2] = $course_appreciations[$key]['COUNT(value)'];
                endforeach;

                // STUDENT VOTE
                $evaluator_students = array_column($voted, 'evaluator_student');
                $key = array_search($course_group_more_informations['username'], $evaluator_students);
                $vote = false;
                if (false !== $key) {
                    $vote = true;
                }

                // RED CARDS
                $usernames = array_column($red_cards, 'username');
                $keys = array_keys($usernames, $course_group_more_informations['username']);
                $red_cards_user = array();
                foreach ($keys as $key) :
                    array_push($red_cards_user, $red_cards[$key]);
                endforeach;

                if (count($red_cards_user) != 0 && empty($red_cards_user[0][5])) {
                    $red_card_current = 1;
                    $red_card_id_current = $red_cards_user[0]['id'];
                    $red_cards_archived = count($red_cards_user) - 1;
                } else {
                    $red_card_current = 0;
                    $red_cards_archived = count($red_cards_user);
                }

            ?>
                <tr class="<?= $trClasse ?>">
                
                    <!-- GROUP -->
                    <td>
                        <span class="hide reference"><?= $course_parameters['id_course'] ?></span>

                        <span class="sort--group"><?= $course_group_more_informations['name'];  ?></span>
                        <a href="#" class="group-graphic" data-id="<?= $course_group_more_informations['id_group'] ?>">
                            <?= $course_group_more_informations['name'];  ?>
                        </a>
                    </td>

                    <!-- FIRSTNAME LASTNAME + VOTE -->
                    <td class="col--large sort--name" data-name="<?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'];  ?>">
                        <a href="#" class="student-graphic" data-id="<?= $course_group_more_informations['username'] ?>">
                            <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname']; ?>
                        </a>
                        <?php
                        if ($vote) :
                            echo "<div class=voted--yes></div>";
                        else :
                            echo "<div class=voted--no></div>";
                        endif;
                        ?>
                    </td>


                    <!-- RED CARD ARCHIVED -->
                    <td class="eval eval--old eval--archive">

                        <?php if ($red_cards_archived != 0) : ?>
                            <div class="alerts__button sort--archive">
                                <?= $red_cards_archived ?>
                            </div>
                        <?php endif; ?>

                    </td>

                    <!-- CURRENT CARD ARCHIVED -->
                    <td class="eval eval--old eval--red">
                        <?php if ($red_card_current != 0) : ?>
                            <button class="button bar__button button--account button--red sort--red red-card" value="<?= $red_card_id_current ?>" aria-expanded="false" data-tippy-content="Carton de <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'];  ?>">
                                <?= $red_card_current; ?>
                            </button>
                        <?php endif; ?>
                    </td>

                    <!-- APPRECIATION -->
                    <?php for ($i = 0; $i <= 3; $i++) : ?>
                        <td class="eval eval--<?= $i ?>">
                            <?php if ($appreciations[$i] != 0) : ?>
                                <div class="alerts__button sort--<?= $i ?>">
                                    <?= $appreciations[$i] ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                </tr>

            <?php
            endforeach;
            ?>
        </tbody>
    </table>
</div>

<div>
    <style>
        .gauge {
            width: 280px;
            height: 40px;
            background-color: #ddd;
            position: relative;
            z-index: 2;
            border-radius: 20px;
            overflow: hidden;
        }

        .gauge__text {
            z-index: 3;
            position: absolute;
            line-height: 40px;
            padding: 0 20px;
            top: 0;
            left: 0;
            width: 280px;
            height: 40px;
        }

        .gauge__progress {
            z-index: 2;
            position: absolute;
            top: 0;
            left: 0;
            height: 40px;
            background-color: #6ce;
        }
    </style>
</div>

<script>
    $gauge = document.querySelector('.gauge')
    $progress = document.querySelector('.gauge__progress')
    $width = $gauge.offsetWidth;
    $student = <?= count($course_groups); ?>;

    $vote = <?= count($current_votes); ?>;

    $newWidth = ($vote * $width) / $student;
    $progress.style.width = $newWidth + 'px';
    var answer = document.getElementById("copyAnswer");
    var textToCopy = document.querySelector('#tocopy')
    var copy = document.querySelector('.js-copy')
    copy.addEventListener('click', function(e) {
        // copy the span content and affect it to the created textarea
        var textArea = document.createElement("textarea");
        textArea.value = textToCopy.textContent;
        document.body.appendChild(textArea);
        // Select some text (you could also create a range)
        textArea.select();
        // Use try & catch for unsupported browser
        try {
            // The important part (copy selected text)
            var ok = document.execCommand('copy');
            if (ok) answer.innerHTML = 'Copied!';
            else answer.innerHTML = 'Unable to copy!';
        } catch (err) {
            answer.innerHTML = 'Unsupported Browser!';
        }
        //remove the textarea
        textArea.remove();
    });

    if (<?= $send ?> == 1) {
        $("#send-mail").attr("disabled", true);
        $(".jauge-go").after("<p>Le rappel a été envoyé. </p>");
    }
</script>