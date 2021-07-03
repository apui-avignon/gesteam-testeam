<?php
class History extends ComponentController
{
    // Save appreciations given
    public function index()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $this->loadModel('CourseParameters');
            $course_parameters = $this->CourseParameters->findById($_SESSION['course_id']);

            $this->loadHelper('Date');
            // Calculate the start and end dates of the graphic
            $date = $this->Date->dateCalculation($course_parameters, date('Y-m-d'), 3);
            $first_date_graphic = $date[0];
            $last_date_graphic = $date[1];
            $last_evaluation_date = $date[2];
            $evaluation_week = date('W',  strtotime(date('Y-m-d', strtotime('-7 day', strtotime($last_date_graphic)))));

            // Count the number of red and yellow cards
            $this->loadModel('Card');
            $current_card = $this->Card->current($_SESSION['course_id'], $_SESSION['user'], $last_date_graphic);
            $red_card = 0;
            $yellow_cards = 0;
            if (isset($current_card[0])) {
                if ($current_card[0]['color'] == 'red') {
                    $red_card = 1;
                } else {
                    $yellow_cards = $current_card[0]['COUNT(*)'];
                }
            }

            $this->loadModel('CourseGroup');
            $group_id = $this->CourseGroup->findByUsername($_SESSION['course_id'], $_SESSION['user'])['id'];

            $this->loadModel('Appreciation');
            // Appreciation of the last week of evaluation 
            $course_appreciations = $this->Appreciation->findByCriteria($_SESSION['user'], $last_date_graphic, $_SESSION['course_id']);
            // Summary of appreciations for the chart
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_SESSION['course_id'],  $group_id, $_SESSION['user']);

            $elements = $this->graphicElements($course_parameters, $last_date_graphic, $graphic_results);
            $array_dates = $elements[0];
            $array_weeks = $elements[1];
            $array_appreciations = $elements[2];

            $this->render('index', compact('course_parameters', 'evaluation_date', 'course_appreciations', 'evaluation_week', 'red_card', 'yellow_cards', 'first_date_graphic', 'last_date_graphic', 'group_id', 'graphic_results', 'last_evaluation_date', 'array_weeks', 'array_dates', 'array_appreciations'));
        } else {
            $this->render('../layouts/error');
        }
    }

    public function graphic()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $this->loadModel('CourseParameters');
            $course_parameters = $this->CourseParameters->findById($_SESSION['course_id']);

            $this->loadHelper('Date');
            $date = $this->Date->dateCalculation($course_parameters, date('Y-m-d'), 3);
            $first_date_graphic = $date[0];
            $last_date_graphic = $date[1];
            $last_evaluation_date = $date[2];

            $this->loadModel('CourseGroup');
            $group_id = $this->CourseGroup->findByUsername($_SESSION['course_id'], $_SESSION['user'])['id'];

            // Summary of appreciations for the chart
            $this->loadModel('Appreciation');
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_SESSION['course_id'],  $group_id, $_SESSION['user']);

            $elements = $this->graphicElements($course_parameters, $last_date_graphic, $graphic_results);
            $array_dates = $elements[0];
            $array_weeks = $elements[1];
            $array_appreciations = $elements[2];

            $this->render('graphic', compact('course_parameters', 'first_date_graphic', 'last_date_graphic', 'group_id', 'graphic_results', 'last_evaluation_date', 'array_weeks', 'array_dates', 'array_appreciations'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function details()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true && isset($_POST['date'])) {

            $this->loadModel('CourseParameters');
            $course_parameters = $this->CourseParameters->findById($_SESSION['course_id']);

            $this->loadHelper('Date');
            $evaluation_week = date('W',  strtotime(date('Y-m-d', strtotime('-7 day', strtotime($_POST['date'])))));

            // Appreciation of the last week of evaluation 
            $this->loadModel('Appreciation');
            $course_appreciations = $this->Appreciation->findByCriteria($_SESSION['user'], $_POST['date'], $_SESSION['course_id']);

            $this->render('details', compact('course_appreciations', 'evaluation_week'));
        } else {
            $this->render('../layouts/error');
        }
    }

    public function graphicElements($course_parameters, $last_date_graphic, $graphic_results)
    {
        // Fill array for the graphic

        $nb_week = 3;
        // Array of week numbers
        $array_dates = array();
        $array_weeks = '[';
        for ($i = $nb_week; $i > 0; $i--) {
            $array_weeks .= "'S " . date("W", strtotime("- " . (($course_parameters['period']) * $i - 1) . " week", strtotime($last_date_graphic))) . "',";
            array_push($array_dates,  date('Y-m-d', strtotime('-' . ($course_parameters['period'] * 7 * ($i - 1)) . 'day', strtotime($last_date_graphic))));
        }
        $array_weeks = substr($array_weeks, 0, -1) . ']';

        // Fill array for the graphic
        $array_bool = array_fill(0, 3, 0);
        $array_y =  array_fill(0, 3, 0);
        $cpt = 0;
        for ($i = $nb_week - 1; $i >= 0; $i--) {
            if ($array_dates[$i] <= $course_parameters['start_date']) {
                break;
            }
            if (isset($graphic_results[$cpt]) && ($array_dates[$i] == $graphic_results[$cpt]['date'])) {
                $array_y[$i] = $graphic_results[$cpt]['SUM(value)'] / ($graphic_results[$cpt]['COUNT(*)']);
                $array_bool[$i] = 1;
                $cpt++;
            }
        }

        $array_appreciations = '[';
        $cpt = 0;
        while ($cpt < $nb_week) {
            $array_appreciations = $array_appreciations . '{x:' . $cpt . ',';
            if ($array_y[$cpt] !== '') {
                $array_appreciations = $array_appreciations . 'y:' . $array_y[$cpt] . ',bool:' . $array_bool[$cpt];
            }
            $array_appreciations = $array_appreciations . '},';
            $cpt++;
        }
        $array_appreciations = substr($array_appreciations, 0, -1) . ']';

        $elements = array();
        array_push($elements, $array_dates, $array_weeks, $array_appreciations);
        return $elements;
    }
}
