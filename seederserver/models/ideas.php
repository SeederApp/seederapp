<?php
/**
 * The Ideas Model does the back-end heavy lifting for the Ideas Controller
 */
class Ideas_Model{
	/**
	 * Holds instance of database connection
	 */
	private $db;
	
	public function __construct(){
		$this->db = new Mysql_Driver;
	}

	/*
	 * @params[0] email
	 * @params[1] idCategory
	 * @params[2] title
	 * @params[3] description
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function addIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO Idea (email, firstName, lastName, gender, salt, hash, photoURL, coins) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5');");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	public function getAllIdeas(){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT * FROM Idea;");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	public function getIdeaById($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT * FROM Idea WHERE idIdea = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>