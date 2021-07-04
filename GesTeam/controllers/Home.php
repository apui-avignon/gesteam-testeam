<?php
class Home extends Controller
{
    public function index()
    {
        session_start();
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true && isset($_SESSION['course_id'])) {
            $course_id = $_SESSION['course_id'];

            $this->loadModel('CourseGroup');
            $group_id = $this->CourseGroup->findByUsername($course_id, $_SESSION['user'])['id'];
            
            // verify if student is allowed to use the application
            if (empty($group_id)) {
                $this->render('../layouts/not_allowed');
            } else {
                $group = $this->CourseGroup->findById($group_id,  $_SESSION['user']);

                $this->loadModel('CourseParameters');
                $course_parameters = $this->CourseParameters->findById($course_id);

                $this->loadHelper('Date');
                $evaluation_date = $this->Date->getDayOfEvaluation($course_parameters['start_date'], $course_parameters['period']);
                $next_evaluation_date = $this->Date->whatDate($evaluation_date, '+', $course_parameters['period'] * 7); 

                $this->loadModel('Appreciation');
                $appreciations = $this->Appreciation->appreciationByEvaluator($next_evaluation_date, $course_id, $_SESSION['user']);

                $criteria_name = array('Moteur',  'Vérificateur', 'Focus', 'Social');

                $nb_member = count($group);

                // Appreciation received on the 4 criteria
                $criteria = array(
                    array_fill(0, $nb_member, 0),
                    array_fill(0, $nb_member, 0),
                    array_fill(0, $nb_member, 0),
                    array_fill(0, $nb_member, 0),
                );

                if (count($appreciations) == 0) {
                    $voted = false;
                } else {
                    $voted = true;
                    $cpt = 0;
                    foreach ($appreciations as $appreciation) :
                        $array_nb = intval($cpt / $nb_member);
                        $criteria[$array_nb][$cpt % $nb_member] = $appreciation['value'];
                        $cpt++;
                    endforeach;
                }
                $this->render('index', compact('voted', 'course_id', 'group', 'appreciations', 'group_id', 'criteria', 'nb_member', 'criteria_name'));
            }   
        } else {
            if (isset($_GET['course_id'])) {
                $course_id = $_GET['course_id'];
                $this->render('../login/index', compact('course_id'));
            } else {
                $this->render('../layouts/not_allowed');
            }
        }
    }
}
