<?php
class Group extends Model
{
    public function __construct()
    {
        $this->table = "group_name";
        $this->getConnexion();
    }


    // Insert group informations
    public function insert(int $id_group, string $name)
    {
        $sql =    "	INSERT IGNORE INTO " . $this->table . " (id_group, name)
					VALUES ('" . $id_group . "', '" . addslashes($name) . "')
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Delete group informations
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM " . $this->table . " 
                    WHERE id_group IN ( 
                        SELECT DISTINCT(id) 
                        FROM course_group 
                        WHERE id_course = '" . $id_course . "'
                    )";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Find group information with id course
    public function findById($id_course)
    {
        $sql =  "	SELECT DISTINCT(id), name 
                    FROM " . $this->table . "  
                    JOIN course_group ON id = id_group 
                    WHERE id_course = '" . $id_course . "' ORDER BY name ASC
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
