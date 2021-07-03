<?php
class Home extends Controller
{
    public function index()
    {
        session_start();
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // User moodle infortmation
            $this->loadModel('UserMoodle');
            $user_identity = $this->UserMoodle->identity($_SESSION['user']);
            $this->loadModel('User');
            $this->User->insert($_SESSION['user'], $user_identity['lastname'], $user_identity['firstname']);

            $this->loadModel('Course');
            $teachersCourses = $this->Course->findByTeacher($_SESSION['user']);

            $this->loadModel('User');
            $user_identity = $this->User->identity($_SESSION['user']);

            $this->loadModel('Card');
            $red_card_activated = $this->Card->redActivated($_SESSION['user']);

            $this->loadModel('CourseMoodle');
            $teacher_s_courses_moodle = $this->CourseMoodle->findByTeacher($_SESSION['user']);

            $this->loadModel('Course');
            $teacher_s_courses = $this->Course->findByTeacher($_SESSION['user']);

            $this->loadModel('Card');
            $cards = $this->Card->currentRed("2021-01-01", $_SESSION['user']);

            $this->render('index', compact('teachersCourses', 'nbcourse', 'user_identity', 'red_card_activated', 'teacher_s_courses_moodle', 'teacher_s_courses', 'cards'));
        } else {
            $configs = include('config.php');
            header('Location: https://' . $configs['server_name'] . '/' . APP_NAME . '/index.php?p=login');
        }
    }
}
