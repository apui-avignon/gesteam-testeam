<?php
class User extends Model
{
    public function __construct()
    {
        $this->table = "user";
        $this->getConnexion();
    }


    // Insert new user
    public function insert(string $username, string $firstname, string $lastname)
    {
        $sql = "INSERT IGNORE INTO " . $this->table . " VALUES ('" . $username . "', '" . $firstname . "', '" . $lastname . "')";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }


    // Get user identity
    public function identity(string $username)
    {
        $sql = "SELECT firstname, lastname FROM " . $this->table . " WHERE username = '" . $username . "'";
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
}
