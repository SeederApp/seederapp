<?php
/**
 * The Comments Model does the back-end heavy lifting for the Comments Controller
 */
class Comments_Model {
	/**
	 * Holds instance of database connection
	 */
	private $db;
	
	public function __construct() {
		$this->db = new Mysql_Driver;
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @params[2] content
	 * @hash hash sent by the client
	 */
	public function addComment($params, $hash) {
		//Authenticate user
		$userHash = $this->getHashByEmail($params[0]);
		$decodedHash = json_decode($userHash, true);
		
		//Check if the sent client-side hash is different than the one in the database
		if ($decodedHash[0][0][0] != $hash){
			return;
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO Comment (date, content) VALUES (now(), '".$params[2]."');");
		
		//Execute query and return "true" or "false"
		if ($this->db->query()){
			//Prepare query
			$this->db->prepare("INSERT INTO Idea_Comment (idIdea, idComment) VALUES ('".$params[1]."', '".$this->db->insert_id."');");
			
			//Execute query and return "true" or "false"
			if ($this->db->query()){
				//Prepare query
				$this->db->prepare("INSERT INTO User_Comment (idUser, idComment) VALUES ('".$params[0]."', '".$this->db->insert_id."');");
				
				//Return "true" or "false"
				return $this->db->query();
			}
		}
	}

	public function getCommentsByIdIdea($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT Comment.* FROM Comment JOIN Idea_Comment WHERE Comment.idComment = Idea_Comment.idComment AND Idea_Comment.idIdea = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>