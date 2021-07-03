<?php
abstract class Model
{
    // BDD informations
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";

    private $host_moodle = "";
    private $db_name_moodle = "";
    private $username_moodle = "";
    private $password_moodle = "";

    protected $_connexion;
    public $table;
    public $id;

    public function getConnexion()
    {
        $this->_connexion = null;
        try {
            $this->_connexion = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->_connexion->exec("set names utf8");
        } catch (PDOException $exception) {
            echo 'Erreur : ' . $exception->getMessage();
        }
    }

    public function getConnexionMoodle()
    {
        $this->_connexion = null;
        try {
            $this->_connexion = new PDO("mysql:host=" . $this->host_moodle . ";dbname=" . $this->db_name_moodle, $this->username_moodle, $this->password_moodle);
            $this->_connexion->exec("set names utf8");
        } catch (PDOException $exception) {
            echo 'Erreur : ' . $exception->getMessage();
        }
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }

    public function getOne()
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id=" . $this->id;
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
}
