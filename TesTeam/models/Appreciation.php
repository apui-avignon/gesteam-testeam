<?php
class Appreciation extends Model
{
    public function __construct()
    {
        $this->table = "appreciation";
        $this->getConnexion();
    }


    // Delete a course with id
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM " . $this->table . " 
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }

    public function deleteCriteria($id_course)
    {
        $sql =    "	DELETE FROM criteria
					WHERE id_appreciation IN ( 
                        SELECT id 
                        FROM " . $this->table . "  
                        WHERE id_course = '" . $id_course . "'
                    )
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }


    // Find appreciations for a specific date
    public function findByDate($date, $id_course, $id_group = "%")
    {
        $sql =    "	SELECT evaluated_student, id_group, value, COUNT(value) 
                    FROM " . $this->table . " 
                    JOIN criteria ON id = id_appreciation 
                    WHERE date = '" . $date . "' AND id_course = '" . $id_course . "' AND id_group LIKE '" . $id_group . "'
                    GROUP BY id_course, id_group, evaluated_student,value
                    ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Check the users who voted
    public function voted($date, $id_course)
    {
        $sql = "SELECT DISTINCT(evaluator_student) 
                FROM " . $this->table . " 
                WHERE id_course = '" . $id_course . "'  AND date = '" . $date . "' ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Return all appreciations received between two date - used for the history view
    public function beetweenTwoDates($start_date, $end_date, $id_course, $id_group = "%", $username = "%")
    {
        $sql = "SELECT date, COUNT(*), SUM(value) 
                FROM " . $this->table . " 
                JOIN criteria ON id = id_appreciation 
                 WHERE date > '" . $start_date . "' AND date <= '" . $end_date . "' AND id_course = '" . $id_course . "'  AND id_group LIKE '" . $id_group . "' AND evaluated_student LIKE '" . $username . "' 
                 GROUP BY date 
                 ORDER BY date DESC ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Count number of appreciations received between two dates - used for the history view
    public function count($start_date, $end_date, $id_course)
    {
        $sql = "SELECT COUNT(DISTINCT(evaluator_student)) as sum
               FROM " . $this->table . " 
                WHERE date > '" . $start_date . "' AND date <= '" . $end_date . "' AND id_course = '" . $id_course . "'
                GROUP BY date 
                ORDER BY date DESC ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Return all appreciations received by criteria for a student
    public function findByCriteria($username, $date, $id_course)
    {
        $sql = "SELECT id_criteria, value, COUNT(value)
                FROM " . $this->table . " 
                JOIN criteria ON id = id_appreciation
                WHERE evaluated_student = '" . $username . "' AND date = '" . $date . "' AND id_course = '" . $id_course . "'
                GROUP BY id_criteria, value 
                ORDER BY id_criteria, value ASC ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // User who did not vote
    public function notVoted($date, $id_course)
    {
        $sql = "SELECT username
                FROM course_group
                WHERE id_course = '" . $id_course . "' AND username NOT IN (
                    SELECT evaluator_student 
                    FROM " . $this->table . "  
                    WHERE id_course = '" . $id_course . "' AND date = '" . $date . "'
                )";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    // find yellow card a specific date
    public function findYellowCard($id_course, $date)
    {
        $sql = "SELECT id_course, date, evaluated_student, COUNT(*) 
                FROM " . $this->table . "
                JOIN criteria ON id_appreciation = id  
                WHERE date = '" . $date . "' AND id_course = '" . $id_course . "' AND value = '-2'
                GROUP BY id_course, date, evaluated_student ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
