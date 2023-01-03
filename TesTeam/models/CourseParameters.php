<?php
class CourseParameters extends Model
{
    public function __construct()
    {
        $this->table = "course_parameters";
        $this->getConnexion();
    }


    // Insert course parameters
    public function insert(int $id_course, string $course, string $start_date, string $end_date, int $threshold_red_card, int $period)
    {
        $sql =    "	INSERT IGNORE INTO " . $this->table . "  (id_course, course, start_date, end_date, threshold_red_card, period)
                    VALUES ('" . $id_course . "','" . addslashes($course) . "', '" . $start_date . "', '" . $end_date . "', '" . $threshold_red_card . "', '" . $period . "')
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
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


    // Update course parameters
    public function update(int $id_course, string $start_date, string $end_date, int $threshold_red_card)
    {

        $sql = " UPDATE " . $this->table . " 
            SET start_date = '" . $start_date . "', end_date = '" . $end_date . "', threshold_red_card = '" . $threshold_red_card . "'
            WHERE id_course = '" . $id_course . "'
            ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Delete course parameters
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM  " . $this->table . " 
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }

    public function all()
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
