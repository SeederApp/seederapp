<?php
/**
 * The Users Model does the back-end heavy lifting for the Users Controller
 */
class Users_Model {
	/**
	 * Holds instance of database connection
	 */
	private $db;
	
	public function __construct() {
		$this->db = new Mysql_Driver;
	}
  
   public function getSalt ($userEmail) 
    {
      //connect to database
      $this->db->connect();
      //prepare query
      $this->db->prepare
      (
        "
        SELECT salt 
        FROM User
        WHERE email = '".$userEmail."';
        "
      );
      //execute query
      $this->db->query();        
      
      $article = $this->db->fetch('array');        

      return $article;
    }  
    
    private function getHash ($userEmail) 
    {
      //connect to database
      $this->db->connect();
      //prepare query
      $this->db->prepare
      (
        "
        SELECT hash 
        FROM User
        WHERE email = '".$userEmail."';
        "
      );
      //execute query
      $this->db->query();        
      
      $article = $this->db->fetch('array');        

      return $article;
    }  
  
	public function getUserById($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins FROM User WHERE idUser = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
	
	public function getUserByEmail($params, $hash) {
		$userHash = $this->getHash($params[0]);
    $decodedHash = json_decode($userHash, true);
    
    if ($decodedHash[0][0][0] != $hash)
      return;
    
    //Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins FROM User WHERE email = '".$params['0']."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
	
		public function getDeveloperByEmail($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT User.email, User.firstName, User.lastName, User.gender, User.photoURL, User.coins, Developer.* FROM User JOIN Developer WHERE User.idUser = Developer.idUser AND User.email = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>