<?php
class CourseGroup extends Model
{
    public function __construct()
    {
        $this->table = "course_group";
        $this->getConnexion();
    }

    // Find group number by username and course id
    public function findByUsername(string $id_course, string $username)
    {
        $sql =    "	SELECT id FROM " . $this->table . "
        			WHERE id_course = '" . $id_course . "' AND username = '" . $username . "' LIMIT 1
        			";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    // Find group member
    public function findById(string $id_group, string $username)
    {
        $sql =    "	SELECT * FROM " . $this->table . "
                    JOIN user ON user.username = " . $this->table . ".username
    				WHERE id = '" . $id_group . "' AND " . $this->table . ".username !=  '" . $username . "'
    			";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

}
