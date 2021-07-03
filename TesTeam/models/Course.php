<?php
class Course extends Model
{
    public function __construct()
    {
        $this->table = "teacher_s_course";
        $this->getConnexion();
    }

    // Find course with the teacher's username
    public function findByTeacher(string $teacher)
    {
        $sql = "SELECT * FROM " . $this->table . " 
                JOIN course_parameters ON course_parameters.id_course = " . $this->table . ".id_course 
                WHERE username = '" . $teacher . "' 
                ORDER BY course ASC
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Find course by ID
    public function findById(string $course_id)
    {
        $sql = "SELECT * FROM teacher_s_course 
        JOIN course_parameters 
        ON teacher_s_course.id_course = course_parameters.id_course
        WHERE teacher_s_course.id_course = '" . $course_id . "' ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    // Insert new course
    public function insert(string $id_course, string $teacher)
    {
        $sql =    "INSERT IGNORE INTO " . $this->table . " (id_course, username, owner)
                VALUES ('" . $id_course . "', '" . $teacher . "', '1')";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Delete course
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM " . $this->table . " 
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }
}
