<?php
class UserMoodle extends Model{
    
    public function __construct(){
        $this->table = "mdl_user";
        $this->getConnexionMoodle();
    }


    // Get user identity in Moodle BDD
    public function identity(string $username) {
        $sql = "SELECT firstname, lastname 
                FROM ".$this->table." 
                WHERE username='".$username."'";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

}