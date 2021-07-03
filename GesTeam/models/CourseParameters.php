<?php
class CourseParameters extends Model
{
    public function __construct()
    {
        $this->table = "course_parameters";
        $this->getConnexion();
    }

    // Find course by id course
    public function findById(string $id_course)
    {
        $sql = "SELECT * FROM " . $this->table . "
                WHERE id_course = '" . $id_course . "'
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
}
