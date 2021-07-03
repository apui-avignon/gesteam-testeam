<?php
class CourseGroup extends Model
{
    public function __construct()
    {
        $this->table = "course_group";
        $this->getConnexion();
    }


    // Insert course group
    public function insert(string $id, string $id_course, string $username)
    {
        $sql =    " INSERT INTO " . $this->table . " (id, id_course, username)
					VALUES ('" . $id . "', '" . $id_course . "', '" . $username . "') ON DUPLICATE KEY UPDATE id='" . $id . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Delete course group
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM " . $this->table . "
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Find group by id course
    public function findById(string $id_course)
    {
        $sql =    "	SELECT * FROM " . $this->table . "
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Delete student in a group
    public function deleteStudent($username, $id_course)
    {
        $sql =  "	DELETE FROM " . $this->table . "
					WHERE username = '" . $username . "' AND id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // More information about group(s)
    public function findByIdMoreInformations($id_course, $id_group = "%")
    {
        $sql =  "	SELECT " . $this->table . ".username, firstname, lastname, id_group, name 
                    FROM " . $this->table . " 
                    JOIN user ON " . $this->table . ".username = user.username 
                    JOIN group_name ON id_group = id 
                    WHERE id_course = '" . $id_course . "' AND id_group LIKE '" . $id_group . "' 
                    ORDER BY name, firstname, lastname ASC
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Count groups in a course
    public function count($id_course)
    {
        $sql =  "	SELECT COUNT(*) 
                    FROM " . $this->table . " 
                    WHERE id_course = '" . $id_course . "'
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
}
