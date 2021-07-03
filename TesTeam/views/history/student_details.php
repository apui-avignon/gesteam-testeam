<div class="alerts__item alerts__week">
    <p class="text">Evaluation reçue pour cette étudiant pour chaque catégorie</p>
    <h2 class="h3">Semaine <?= date('W',  strtotime(date('Y-m-d', strtotime('-7 day', strtotime($date))))); ?></h2>

    <div class="alerts__block">
        <?php

        $array = array('critere_1', 'critere_2', 'critere_3', 'critere_4');
        $criteria_number = 0;

        $htmlLine = '';
        $toggle = false;

        while ($criteria_number  < 4) {

            // CRITERIA
            $id_criteria = array_column($course_appreciations, 'id_criteria');
            $keys = array_keys($id_criteria, $criteria_number);
            $criteria_appreciation = array();
            foreach ($keys as $key) :
                array_push($criteria_appreciation, $course_appreciations[$key]);
            endforeach;

            $bool = false;



            $htmlLine .= '					<div class="alerts__line">' . "\r\n";
            $htmlLine .= '						<h3 data-cat="';
            $htmlLine .= $criteria_number   + 1;
            $htmlLine .= '" data-tippy-content="';
            $htmlLine .= $array[$criteria_number];
            $htmlLine .= '" class="alerts__title">';
            $htmlLine .= $array[$criteria_number];
            $htmlLine .= '</h3>' . "\r\n";


            $apprecition_level = -2;
            $cpt = 0;
            while ($apprecition_level < 2) {
                if (isset($criteria_appreciation[$cpt]) && $criteria_appreciation[$cpt]['value'] == $apprecition_level) {
                    $bool = true;

                    $htmlbutton = '						<p class="alerts__button" data-eval="';
                    $htmlbutton .= $apprecition_level;
                    $htmlbutton .= '">';
                    $htmlbutton .= $criteria_appreciation[$cpt]['COUNT(value)'];
                    $htmlbutton .= '</p>' . "\r\n";
                    $htmlLine .= $htmlbutton;
                    if (count($criteria_appreciation) - 1 > $cpt) {
                        $cpt += 1;
                    }
                } else {
                    $htmlLine .=  '<p class="dot"></p>' . "\r\n";
                }
                $apprecition_level += 1;
            }


            if ($bool == false) {

                $toggle = true;

                $htmltext = '<p class="alerts__text">Pas d\'évènement</p>' . "\r\n";
                $htmlLine .= $htmltext;
            }
            $criteria_number++;

            $htmlLine .= '					</div>' . "\r\n";
        }

        if ($toggle == false) {
            echo $htmlLine;
        } else {
            echo '<p class="alerts__text">Pas d\'évènement</p>';
        }
        ?>
    </div>
</div>

<script>
    document.getElementById("graph-details").style.display = "block";
</script>