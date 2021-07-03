<?php
class Form extends ComponentController
{
    public function add()
    {
        if (isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // find teachers' courses on moodle
            $this->loadModel("CourseMoodle");
            $teacher_s_courses_moodle = $this->CourseMoodle->findByTeacher($_SESSION['user']);

            // find teachers' courses on the app
            $this->loadModel("Course");
            $teacher_s_courses = $this->Course->findByTeacher($_SESSION['user']);

            $this->render('add',  compact('teacher_s_courses_moodle', 'teacher_s_courses'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function edit()
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Get course parameters for editing course
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->render('edit',  compact('course_parameters'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function delete()
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Get course parameters for deleting course
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->render('delete',  compact('course_parameters'));
        } else {
            $this->render('../layouts/error');
        }
    }


    public function update()
    {
        if (isset($_POST['course_id']) && isset($_SESSION['connected']) && $_SESSION['connected'] == true) {
            // Get course parameters for updating course
            $this->loadModel("CourseParameters");
            $course_parameters = $this->CourseParameters->findById($_POST['course_id']);

            $this->render('update',  compact('course_parameters'));
        } else {
            $this->render('../layouts/error');
        }
    }
}
