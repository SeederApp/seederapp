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
	 * @email email
	 */
	private function getHashByEmail($email) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT hash FROM User WHERE email = '".$email."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	private function authenticateUser($email, $hash) {
		$userHash = $this->getHashByEmail($email);
		$userHashDecoded = json_decode($userHash, true);
		if ($userHashDecoded != null) {
			if ($userHashDecoded[0][0][0] != $hash) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/*
	 * @params[0] email
	 * @params[1] idUser
	 * @params[2] idIdea
	 * @params[3] content
	 * @hash hash sent by the client
	 */
	public function addComment($params, $hash) {
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO Comment (date, content) VALUES (now(), '".$params[3]."');");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Prepare query
      $idComment = $this->db->fetchId();
			$this->db->prepare("INSERT INTO Idea_Comment (idIdea, idComment) VALUES ('".$params[2]."', '".$idComment."');");
		
			//Execute query and return "true" or "false"
			if ($this->db->query() == 1){
				//Prepare query
				$this->db->prepare("INSERT INTO User_Comment (idUser, idComment) VALUES ('".$params[1]."', '".$idComment."');");
				
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