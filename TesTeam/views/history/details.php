<p class="text">Evaluation reçue pour tout les étudiants du groupe</p>
<h2 class="h3">Semaine <?= date('W',  strtotime(date('Y-m-d', strtotime('-7 day', strtotime($date))))); ?></h2>

<div class="body__content">
    <table id="table-course">
        <thead>
            <tr>
                <th>Groupe</th>
                <th class="col--large">Nom</th>
                <th>Archive</th>
                <th>Carton</th>
                <th>M&eacute;diocre</th>
                <th>Passable</th>
                <th>Bien</th>
                <th>Tr&egrave;s&nbsp;bien</th>
            </tr>
        </thead>

        <?php
        // FOR ALL STUDENT IN THIS COURSE
        foreach ($course_groups_more_informations as $course_group_more_informations) :
            $appreciations = array(0, 0, 0, 0);

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

            if (count($red_cards_user) != 0 && empty($red_cards_user[0]['deactivated_date'])) {
                $red_card_current = 1;
                $red_card_id_current = $red_cards_user[0]['id'];
                $red_cards_archived = count($red_cards_user) - 1;
            } else {
                $red_card_current = 0;
                $red_cards_archived = count($red_cards_user);
            }

        ?>
            <tr>
                <!-- GROUP -->
                <td>
                    <span class="sort--group"><?= $course_group_more_informations['name'];  ?></span>
                        <?= $course_group_more_informations['name'];  ?>
                </td>

                <!-- FIRSTNAME LASTNAME + VOTE -->
                <td class="col--large sort--name" data-name="<?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'];  ?>">
                        <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname']; ?>
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
                        <button class="button button--account button--red sort--red carton-rouge" value="<?= $red_card_id_current ?>" aria-expanded="false" data-tippy-content="Carton de <?= $course_group_more_informations['firstname'] . " " . $course_group_more_informations['lastname'];  ?>">
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