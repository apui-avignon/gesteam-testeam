<?php
class Card extends Model
{
    public function __construct()
    {
        $this->table = "card";
        $this->getConnexion();
    }


    // Return current card with all infomations needed
    public function currentRed(string $date, string $teacher)
    {
        $sql = "SELECT " . $this->table . ".id, " . $this->table . ".id_course, course_group.id as id_group, group_name.name, " . $this->table . ".username, course_parameters.course, user.firstname, user.lastname, " . $this->table . ".deactivation_date, archived.total_red_card 
                FROM " . $this->table . "  
                JOIN teacher_s_course ON " . $this->table . ".id_course = teacher_s_course.id_course 
                JOIN ( 
                    SELECT " . $this->table . ".id_course, " . $this->table . ".username, COUNT(*) as total_red_card 
                    FROM " . $this->table . " 
                    WHERE color = 'red'
                    GROUP BY " . $this->table . ".id_course, " . $this->table . ".username
                ) as archived 
                ON archived.id_course = " . $this->table . ".id_course AND archived.username = " . $this->table . ".username
                JOIN course_parameters ON course_parameters.id_course = " . $this->table . ".id_course 
                JOIN user ON user.username = " . $this->table . ".username 
                JOIN course_group ON " . $this->table . ".id_course = course_group.id_course AND " . $this->table . ".username = course_group.username 
                JOIN group_name ON group_name.id_group = course_group.id 
                WHERE " . $this->table . ".id IN (
                    SELECT max( " . $this->table . ".id) 
                    FROM " . $this->table . "  
                    WHERE color = 'red'  AND teacher_s_course.username = '" . $teacher . "' 
                    GROUP BY " . $this->table . ".id_course, " . $this->table . ".username 
                ) AND (deactivation_date IS NULL OR deactivation_date > '" . $date . "' )
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Find actived red cards
    public function redActivated(string $teacher)
    {
        $sql = "SELECT COUNT(*) 
                FROM " . $this->table . " 
                WHERE id IN (
                    SELECT MAX(id) 
                    FROM " . $this->table . " 
                    JOIN teacher_s_course ON " . $this->table . ".id_course = teacher_s_course.id_course 
                    WHERE teacher_s_course.username='" . $teacher . "'  AND color = 'red' 
                    GROUP BY " . $this->table . ".username, teacher_s_course.id_course
                ) AND deactivation_date IS NULL";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    // Delete card with id
    public function delete($id_course)
    {
        $sql =    "	DELETE FROM " . $this->table . " 
					WHERE id_course = '" . $id_course . "'
				";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }

    public function redByCourse($id_course, $date)
    {
        $sql = "SELECT firstname, lastname 
                FROM " . $this->table . " 
                JOIN user ON card.username = user.username 
                WHERE date =  '" . $date . "' AND id_course =  '" . $id_course . "' AND color = 'red' 
                ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Find cards with specific color
    public function findByColor($id_course, $date, $color)
    {
        $sql = " SELECT * FROM " . $this->table . "  WHERE id_course = '" . $id_course . "' AND color = '" . $color . "' AND date <= '" . $date . "' ORDER BY date DESC";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Return all cards received between two date - used for the history view
    public function beetweenTwoDates($start_date, $end_date, $id_course, $id_group = "%", $username = "%")
    {
        $sql = "SELECT date FROM " . $this->table . " 
                JOIN course_group ON " . $this->table . ".username = course_group.username AND " . $this->table . ".id_course = course_group.id_course 
                WHERE color = 'red' AND date > '" . $start_date . "' AND date <= '" . $end_date . "' AND " . $this->table . ".id_course = '" . $id_course . "'  AND course_group.id LIKE '" . $id_group . "' AND " . $this->table . ".username LIKE '" . $username . "'
                GROUP BY date
                ORDER BY date DESC ";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Find a card by id
    public function findById($id_card)
    {
        $sql = " SELECT * FROM " . $this->table . "  
                WHERE id = '" . $id_card . "'";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    // Get archived cards for a student
    public function archived($username, $id_course, $currend_red_cards)
    {
        $sql = " SELECT COUNT(*) FROM " . $this->table . "  
                WHERE id_course = '" . $id_course . "' AND username = '" . $username . "' AND color = 'red' AND id !=  '" . $currend_red_cards . "'";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    // Yellow card received between 2 dates for 3 weeks * period
    public function yellowHistory($username, $id_course, $first_date, $last_date)
    {
        $sql = " SELECT date, COUNT(*), GROUP_CONCAT(id), deactivation_date FROM " . $this->table . "  
                WHERE color = 'yellow' AND username = '" . $username . "' AND id_course = '" . $id_course . "' AND date >  '" . $first_date . "' AND date <= '" . $last_date . "'
                GROUP BY date
                ORDER BY date DESC";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Update resolved red card 
    public function resolved($id_yellow_cards, $date)
    {
        $sql = " UPDATE " . $this->table . "
                SET deactivation_date = '" . $date . "' 
                WHERE id IN (" . implode(',', $id_yellow_cards) . ")";

        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Update not resolved red card 
    public function notResolved($id_yellow_cards)
    {
        $sql = " UPDATE " . $this->table . "
                SET deactivation_date = NULL
                WHERE id IN (" . implode(',', $id_yellow_cards) . ")";

        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }


    // Insert new red card
    public function insertRed($id_course, $date, $username)
    {
        $sql =    "INSERT INTO " . $this->table . "(id_course, date, username, color, deactivation_date) VALUES ('" . $id_course . "' ,'" . $date . "' ,'" . $username . "' ,'red',NULL)";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }

    // Insert new yellow card
    public function insertYellow($id_course, $date, $username)
    {
        $sql =    "INSERT INTO " . $this->table . "(id_course, date, username, color, deactivation_date) VALUES ('" . $id_course . "' ,'" . $date . "' ,'" . $username . "' ,'yellow',NULL)";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
    }

    // Retrieve students who have received a yellow card for a specific date
    public function currentYellow($id_course, $date)
    {
        $sql = " SELECT DISTINCT(username) FROM " . $this->table . "  
                WHERE color = 'yellow' AND id_course = '" . $id_course . "' AND date = '" . $date . "'";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
