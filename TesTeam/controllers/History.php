<?php
class History extends ComponentController
{
    public function displayGraphic()
    {
        $informations = array();
        $group = "";
        $student = "";
        $votes = "";

        if (isset($_POST['group'])) {
            $group = $_POST['group'];
        }
        if (isset($_POST['student'])) {
            $student = $_POST['student'];
        }

        // Get course parameters
        $this->loadModel("CourseParameters");
        $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

        // Calculation of the days of the first and the last evaluation weeks on 3 weeks * period
        $this->loadHelper('Date');
        $date = $this->Date->dateCalculation($course_parameters, date('Y-m-d'));
        $first_date_graphic = $date[0];
        $last_date_graphic = $date[1];
        $last_evaluation_date = $date[2];

        $this->loadModel('Appreciation');
        $this->loadModel('Card');
        if (empty($group) && empty($student)) {
            $click = false;
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id']);
            $graphic_red_cards = $this->Card->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id']);
            $votes = $this->Appreciation->count($first_date_graphic, $last_date_graphic, $_POST['course_id']);
        } else if (empty($group) && !empty($student)) {
            $click = true;
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'], '%', $student);
            $graphic_red_cards = $this->Card->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'], '%', $student);
        } else if (!empty($group) && empty($student)) {
            $click = true;
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'], $group);
            $graphic_red_cards = $this->Card->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'], $group);
        } else if (!empty($group) && !empty($student)) {
            $click = true;
            $graphic_results = $this->Appreciation->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'],  $group, $student);
            $graphic_red_cards = $this->Card->beetweenTwoDates($first_date_graphic, $last_date_graphic, $_POST['course_id'],  $group, $student);
        }

        $this->loadModel("CourseGroup");
        $nb_of_students = $this->CourseGroup->count($_POST['course_id']);

        $elements = $this->graphicElements($course_parameters, $last_date_graphic, $graphic_results, $votes, $nb_of_students, $group, $student, $graphic_red_cards);

        array_push($informations, $course_parameters, $last_evaluation_date, $graphic_results, $graphic_red_cards, $group, $student, $first_date_graphic, $last_date_graphic, $votes, $click, $nb_of_students, $elements);
        return $informations;
    }


    public function index()
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $informations = $this->displayGraphic();

            // More information about course's group
            $this->loadModel("CourseGroup");
            $course_groups_more_informations = $this->CourseGroup->findByIdMoreInformations($_POST['course_id']);
            $course_groups = $this->CourseGroup->findById($_POST['course_id']);

            $this->loadModel("Group");
            $groups = $this->Group->findById($_POST['course_id']);

            $course_parameters = $informations[0];
            $last_evaluation_date  = $informations[1];
            $graphic_results  = $informations[2];
            $graphic_red_cards = $informations[3];
            $group = $informations[4];
            $student = $informations[5];
            $first_date_graphic = $informations[6];
            $last_date_graphic = $informations[7];
            $votes = $informations[8];
            $click = $informations[9];
            $nb_of_students = $informations[10];
            $array_dates = $informations[11][0];
            $array_weeks = $informations[11][1];
            $array_appreciations = $informations[11][2];
            $array_pourcent = $informations[11][3]; 
            $array_red_cards = $informations[11][4];
            $array_bool = $informations[11][5];

            $this->render('index', compact('course_parameters', 'course_groups_more_informations', 'groups', 'last_evaluation_date', 'graphic_results', 'graphic_red_cards', 'group', 'student', 'first_date_graphic', 'last_date_graphic', 'votes', 'nb_of_students', 'click', 'array_dates', 'array_weeks', 'array_appreciations', 'array_pourcent', 'array_red_cards', 'array_bool'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function graphic()
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $informations = $this->displayGraphic();

            $course_parameters = $informations[0];
            $last_evaluation_date  = $informations[1];
            $graphic_results  = $informations[2];
            $graphic_red_cards = $informations[3];
            $group = $informations[4];
            $student = $informations[5];
            $first_date_graphic = $informations[6];
            $last_date_graphic = $informations[7];
            $votes = $informations[8];
            $click = $informations[9];
            $nb_of_students = $informations[10];
            $array_dates = $informations[11][0];
            $array_weeks = $informations[11][1];
            $array_appreciations = $informations[11][2];
            $array_pourcent = $informations[11][3]; 
            $array_red_cards = $informations[11][4];
            $array_bool = $informations[11][5];

            $this->render('graphic', compact('course_parameters', 'last_evaluation_date', 'graphic_results', 'graphic_red_cards', 'group', 'student', 'first_date_graphic', 'last_date_graphic', 'votes', 'nb_of_students', 'click', 'array_dates', 'array_weeks', 'array_appreciations', 'array_pourcent', 'array_red_cards', 'array_bool'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function graphicElements($course_parameters, $last_date_graphic, $graphic_results, $votes, $nb_of_students, $group, $student, $graphic_red_cards)
    {
        // Fill array for the graphic

        $nb_week = 15;
        // Array of week numbers
        $array_dates = array();
        $array_weeks = '[';
        for ($i = $nb_week; $i > 0; $i--) {
            $array_weeks .= "'S " . date("W", strtotime("- " . (($course_parameters['period']) * $i - 1) . " week", strtotime($last_date_graphic))) . "',";
            array_push($array_dates,  date('Y-m-d', strtotime('-' . ($course_parameters['period'] * 7 * ($i - 1)) . 'day', strtotime($last_date_graphic))));
        }
        $array_weeks = substr($array_weeks, 0, -1) . ']';

        $array_bool = array_fill(0, 15, 0);
        $array_y =  array_fill(0, 15, 0);
        $array_pourcent =  array_fill(0, 15, 0);
        $array_red_cards =  array_fill(0, 15, 0);

        $cpt = 0;
        $cpt_red_card = 0;

        for ($i = $nb_week - 1; $i >= 0; $i--) {
            if ($array_dates[$i] <= $course_parameters['start_date']) {
                break;
            }
            if (isset($graphic_results[$cpt]) && ($array_dates[$i] == $graphic_results[$cpt]['date'])) {
                $array_y[$i] = $graphic_results[$cpt]['SUM(value)'] / ($graphic_results[$cpt]['COUNT(*)']);
                $array_bool[$i] = 1;
                if (empty($group) && empty($student)) {
                    $array_pourcent[$i] = (int)(($votes[$cpt]['sum'] * 100) / $nb_of_students['COUNT(*)']) + 1;
                }
                $cpt++;
            }
            if (isset($graphic_red_cards[$cpt_red_card]) && ($array_dates[$i] == $graphic_red_cards[$cpt_red_card]['date'])) {
                $array_red_cards[$i] = 1;
                $cpt_red_card++;
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
        array_push($elements, $array_dates, $array_weeks, $array_appreciations, $array_pourcent, $array_red_cards, $array_bool);
        return $elements;
    }




    public function display()
    {
        if (isset($_POST['group']) && isset($_POST['student']) && isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $informations = $this->displayGraphic();

            $course_parameters = $informations[0];
            $last_evaluation_date  = $informations[1];
            $graphic_results  = $informations[2];
            $graphic_red_cards = $informations[3];
            $group = $informations[4];
            $student = $informations[5];
            $first_date_graphic = $informations[6];
            $last_date_graphic = $informations[7];
            $votes = $informations[8];
            $click = $informations[9];
            $nb_of_students = $informations[10];
            $array_dates = $informations[11][0];
            $array_weeks = $informations[11][1];
            $array_appreciations = $informations[11][2];
            $array_pourcent = $informations[11][3]; 
            $array_red_cards = $informations[11][4];
            $array_bool = $informations[11][5];

            $this->render('display', compact('course_parameters', 'last_evaluation_date', 'graphic_results', 'graphic_red_cards', 'group', 'student', 'first_date_graphic', 'last_date_graphic', 'votes', 'nb_of_students', 'click', 'array_dates', 'array_weeks', 'array_appreciations', 'array_pourcent', 'array_red_cards', 'array_bool'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function details()
    {
        if (isset($_POST['date']) && isset($_POST['course_id']) && isset($_POST['group']) && isset($_POST['student']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $group = $_POST['group'];
            $student = $_POST['student'];

            // Get course parameters
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->loadModel('CourseGroup');
            $course_groups_more_informations = $this->CourseGroup->findByIdMoreInformations($_POST['course_id'], $group);

            // find the last evaluation date
            $this->loadHelper('Date');
            $evaluation_date = $this->Date->getDayOfEvaluation($_POST['date'], $course_parameters['period'], date('Y-m-d'));

            $this->loadModel('Appreciation');
            $course_appreciations = $this->Appreciation->findByDate($_POST['date'], $_POST['course_id'], $group);
            $voted = $this->Appreciation->voted($_POST['date'], $_POST['course_id']);

            $this->loadModel('Card');
            $red_cards = $this->Card->findByColor($_POST['course_id'], $_POST['date'], "red");

            $date =  $_POST['date'];

            if (empty($student)) {
                $this->render('details',  compact('course_parameters', 'group', 'student', 'course_appreciations', 'course_groups_more_informations', 'voted', 'red_cards', 'date'));
            } else {
                $this->loadModel('Appreciation');
                $course_appreciations = $this->Appreciation->findByCriteria($student, $_POST['date'], $_POST['course_id']);
                $this->render('student_details',  compact('course_parameters', 'group', 'student', 'course_appreciations', 'date'));
            }
        } else {
            $this->render('../layouts/error');
        }
    }
}
