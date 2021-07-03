<?php
class Save extends Controller
{
    public function index()
    {
        // Save appreciations received

        session_start();
        if (isset($_POST['criteria_1']) && isset($_POST['criteria_2']) && isset($_POST['criteria_3']) && isset($_POST['criteria_4']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $this->loadModel('CourseParameters');
            $course_parameters = $this->CourseParameters->findById($_SESSION['course_id']);

            $this->loadModel('CourseGroup');
            $group_id = $this->CourseGroup->findByUsername($_SESSION['course_id'], $_SESSION['user'])['id'];
            $group = $this->CourseGroup->findById($group_id,  $_SESSION['user']);

            $this->loadHelper('Date');
            $evaluation_date = $this->Date->getDayOfEvaluation($course_parameters['start_date'], $course_parameters['period']);
            $next_evaluation_date = $this->Date->whatDate($evaluation_date, '+', $course_parameters['period'] * 7);

            $student_number = 0;
            foreach ($group as $group_member) :
                for ($criteria_number = 1; $criteria_number <= 4; $criteria_number++) {
                    $this->loadModel('Appreciation');
                    $this->Appreciation->insert($_SESSION['course_id'], $next_evaluation_date, $_SESSION['user'], $group_member['username'], $group_id);
                    $appreciation_id = $this->Appreciation->findByEvalStudent($_SESSION['course_id'], $next_evaluation_date, $_SESSION['user'], $group_member['username'], $group_id);
                    $this->Appreciation->insertCriteria($appreciation_id['id'], $criteria_number - 1,  $_POST['criteria_' . $criteria_number][$student_number]);
                }
                $student_number++;
            endforeach;

            $this->render('index');
        } else {
            $this->render('../layouts/error');
        }
    }
}
