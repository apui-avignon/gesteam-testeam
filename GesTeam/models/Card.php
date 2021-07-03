<?php
class Card extends Model
{
    public function __construct()
    {
        $this->table = "card";
        $this->getConnexion();
    }

    // Get the current card for a specific date
    public function current($id_course, $username, $date)
    {
        $sql = "SELECT color, COUNT(*) 
                FROM " . $this->table . " 
                WHERE id_course = '" . $id_course . "' AND username = '" . $username . "' AND date = '" . $date . "' 
                GROUP BY color";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
