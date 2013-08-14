<?php
/**
 * The Categories Model does the back-end heavy lifting for the Categories Controller
 */
class Users_Model
{
     /**
     * Holds instance of database connection
     */
    private $db;
    
    public function __construct()
    {
        $this->db = new Mysql_Driver;
    }

    /**
     * Fetches all users 
     * 
     * @return array $users
     */
    public function getAllUsers()
    {        
        $this->db->connect();
        $this->db->prepare
        (
            "
            SELECT *
            FROM User;
            "
        );
        $this->db->query();
        $users = $this->db->fetch('array');
        
        return $users;
    }

    public function getUserById($params)
     {
        $this->db->connect();
        $this->db->prepare
        (
            "
            SELECT *
            FROM User
            WHERE idUser = ".$params['0'].";
            "
        );
        $this->db->query();
        $article = $this->db->fetch('array');
        
        return $article;
    }
    
    
}
?>
