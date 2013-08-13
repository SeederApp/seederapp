<?php
/**
 * The Categories Model does the back-end heavy lifting for the Categories Controller
 */
class Categories_Model
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
     * Fetches article based on supplied name
     * 
     * @param string $author
     * 
     * @return array $article
     */
    public function getAllCategories()
    {        
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            "
            SELECT *
            FROM Category;
            "
        );
        
        //execute query
        $this->db->query();
        
        $article = $this->db->fetch('array');
        
        return $article;
    }

    public function getAllApps()
    {        
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            "
            SELECT idCategory, name
            FROM Category WHERE categoryType = 'Apps';
            "
        );
        
        //execute query
        $this->db->query();
        
        $article = $this->db->fetch('array');
        
        return $article;
    }
    
    public function getAllGames()
    {        
        //connect to database
        $this->db->connect();
        
        //prepare query
        $this->db->prepare
        (
            "
            SELECT idCategory, name
            FROM Category WHERE categoryType = 'Games';
            "
        );
        
        //execute query
        $this->db->query();
        
        $article = $this->db->fetch('array');
        
        return $article;
    }
    
    public function getCategoryById($params)
    {
      //connect to database
        $this->db->connect();
        //prepare query
        $this->db->prepare
        (
            "
            SELECT *
            FROM Category
            WHERE idCategory = ".$params['0'].";
            "
        );
        
        //execute query
        $this->db->query();
        
        $article = $this->db->fetch('array');
        
        return $article;
    }
    
    public function getCategoryByName($params)
    {
      //connect to database
        $this->db->connect();
        //prepare query
        $this->db->prepare
        (
            "
            SELECT *
            FROM Category
            WHERE name = ".$params['0'].";
            "
        );
        
        //execute query
        $this->db->query();
        
        $article = $this->db->fetch('array');
        
        return $article;
    }
}
?>
