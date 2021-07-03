<?php
class Appreciation extends Model
{
    public function __construct()
    {
        $this->table = "appreciation";
        $this->getConnexion();
    }

    // Find appreciation for a specific date
    public function appreciationByEvaluator($date, $id_course, $username)
    {
        $sql =    "	SELECT * FROM " . $this->table . " 
                    JOIN criteria ON appreciation.id = criteria.id_appreciation 
                    WHERE id_course = '" . $id_course . "' AND evaluator_student = '" . $username . "' AND date = '" . $date . "'
                    ORDER BY id_criteria, evaluated_student ASC
                    ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    // Insert appreciation giving by the evaluator student
    public function insert($id_course, $date, $evaluator_student, $evaluated_student, $id_group)
    {
        $sql = "INSERT IGNORE INTO " . $this->table . "
                (id_course, date, evaluator_student, evaluated_student, id_group) 
                VALUES ('" . $id_course . "', '" . $date . "', '" . $evaluator_student . "', '" . $evaluated_student . "', '" . $id_group . "')";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        // return $this->_connexion->lastInsertId(); 
    }

    // Find the id for a specific evaluation
    public function findByEvalStudent($id_course, $date, $evaluator_student, $evaluated_student, $id_group)
    {
        $sql = "SELECT id 
            FROM appreciation 
            WHERE id_course = '" . $id_course . "' AND evaluator_student = '" . $evaluator_student . "' AND evaluated_student = '" . $evaluated_student . "' AND date = '" . $date . "' AND id_group = '" . $id_group . "' ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    // Insert criteria giving by the evaluator student
    public function insertCriteria($id_appreciation, $id_criteria, $value)
    {
        $sql = "INSERT INTO criteria
        VALUES ('" . $id_appreciation . "', '" . $id_criteria . "', '" . $value . "')  ON DUPLICATE KEY UPDATE value  ='" . $value . "' ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
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
}
