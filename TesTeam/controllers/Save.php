<?php
class Save extends ComponentController
{
    public function add()
    {
        if (isset($_POST['course_name']) && isset($_POST['course_id']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['limit_yellow_card']) && isset($_POST['evaluation_period']) && isset($_SESSION['user']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $this->loadModel("CourseParameters");
            $this->CourseParameters->insert($_POST['course_id'], $_POST['course_name'], $_POST['start_date'], $_POST['end_date'], $_POST['limit_yellow_card'], $_POST['evaluation_period']);

            $this->loadModel("Course");
            $this->Course->insert($_POST['course_id'], $_SESSION['user']);

            $this->loadModel("CourseMoodle");
            $moodle_course_groups = $this->CourseMoodle->group($_POST['course_id']);
            // Add groups from moodle course
            foreach ($moodle_course_groups as $moodle_course_group) :
                $this->loadModel("Group");
                $this->Group->insert($moodle_course_group['id'], $moodle_course_group['name']);

                $this->loadModel("User");
                $this->User->insert($moodle_course_group['username'], $moodle_course_group['firstname'], $moodle_course_group['lastname']);

                $this->loadModel("CourseGroup");
                $this->CourseGroup->insert($moodle_course_group['id'], $_POST['course_id'], $moodle_course_group['username']);

            endforeach;

            $course_name = stripslashes($_POST['course_name']);
            $course_id = $_POST['course_id'];

            $this->render('add', compact('course_name', 'course_id'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function edit()
    {
        if (isset($_POST['course_id']) && isset($_POST['course_name']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['limit_yellow_card']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            $this->loadModel('CourseParameters');
            $this->CourseParameters->update($_POST['course_id'], $_POST['start_date'], $_POST['end_date'], $_POST['limit_yellow_card']);
            $course_name = $_POST['course_name'];
            $course_id = $_POST['course_id'];
            $this->render('edit', compact('course_id', 'course_name'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function delete()
    {
        if (isset($_POST['course_id']) && isset($_POST['course_name']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Deleted all informations about the course


            $this->loadModel('Appreciation');
            $this->Appreciation->deleteCriteria($_POST['course_id']);
           
            $this->loadModel('Appreciation');
            $this->Appreciation->delete($_POST['course_id']);

            $this->loadModel('Card');
            $this->Card->delete($_POST['course_id']);

            $this->loadModel('Group');
            $this->Group->delete($_POST['course_id']);
            
            $this->loadModel('Course');
            $this->Course->delete($_POST['course_id']);

            $this->loadModel('CourseGroup');
            $this->CourseGroup->delete($_POST['course_id']);
            
            $this->loadModel('CourseParameters');
            $this->CourseParameters->delete($_POST['course_id']);

            $course_id = $_POST['course_id'];
            $course_name = $_POST['course_name'];

            $this->render('delete', compact('course_id', 'course_name'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function update()
    {
        if (isset($_POST['course_id']) && isset($_POST['course_name']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {

            $this->loadModel('CourseGroup');
            $course_groups = $this->CourseGroup->findById($_POST['course_id']);

            $this->loadModel('CourseMoodle');
            $moodle_course_groups = $this->CourseMoodle->group($_POST['course_id']);

            // Add or edit groups and users from moodle course
            foreach ($moodle_course_groups as $moodle_course_group) :

                $this->loadModel("Group");
                $this->Group->insert($moodle_course_group['id'], $moodle_course_group['name']);

                $this->loadModel("User");
                $this->User->insert($moodle_course_group['username'], $moodle_course_group['firstname'], $moodle_course_group['lastname']);

                $this->loadModel("CourseGroup");
                $this->CourseGroup->insert($moodle_course_group['id'], $_POST['course_id'], $moodle_course_group['username']);

            endforeach;


            $usernames_moodle = array_column($moodle_course_groups, 'username');
            $usernames = array_column($course_groups, 'username');
            $users_deleted = $result = array_diff($usernames, $usernames_moodle);

            // Remove users who are not in the group or course
            foreach ($users_deleted as $user_deleted) :
                $this->loadModel("CourseGroup");
                $this->CourseGroup->deleteStudent($user_deleted, $_POST['course_id']);
            endforeach;


            $course_id = $_POST['course_id'];
            $course_name = $_POST['course_name'];

            $this->render('update', compact('course_id', 'course_name'));
        } else {
            $this->render('../layouts/error');
        }
    }
}
