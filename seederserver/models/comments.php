<?php
/**
 * The Comments Model does the back-end heavy lifting for the Comments Controller
 */
class Comments_Model{
	/**
	 * Holds instance of database connection
	 */
	private $db;
	
	public function __construct(){
		$this->db = new Mysql_Driver;
	}

	/*
	 * @email email
	 */
	private function getHashByEmail($email){
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
	 * @email email
	 */
	private function getIdByEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idUser FROM User WHERE email = '".$email."';");
		
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
	private function authenticateUser($email, $hash){
		$userHash = $this->getHashByEmail($email);
		$userHashDecoded = json_decode($userHash, true);
		if ($userHashDecoded != null){
			if ($userHashDecoded[0][0][0] != $hash){
				return false;
			}
			return true;
		} else{
			return false;
		}
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @params[2] content
	 * @hash hash sent by the client
	 */
	public function addComment($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		$userIdDecoded = json_decode($this->getIdByEmail($params[0]), true);
		$userId = $userIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO Comment (date, content) VALUES (now(), '".$params[2]."');");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			$idComment = $this->db->fetchId();
			
			//Prepare query
			$this->db->prepare("INSERT INTO Idea_Comment (idIdea, idComment) VALUES ('".$params[1]."', '".$idComment."');");
			
			//Execute query and return "true" or "false"
			if ($this->db->query() == 1){
				//Prepare query
				$this->db->prepare("INSERT INTO User_Comment (idUser, idComment) VALUES ('".$userId."', '".$idComment."');");
				
				//Return "true" or "false"
				return $this->db->query();
			}
		}
	}
  
  /*
	 * @email email
	 */
	private function getUserIdByEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idUser FROM User WHERE email = '".$email."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @email email
	 * @idComment idComment
	 */
	public function validateCommentByEmail($email, $idComment){
		//Connect to database
		$this->db->connect();
		
		//Get user id by email
		$userIdDecoded = json_decode($this->getUserIdByEmail($email), true);
		$userId = $userIdDecoded[0][0][0];
		//Prepare query
		$this->db->prepare("SELECT count(1) FROM User_Comment WHERE idUser = ".$userId." AND idComment = ".$idComment.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @params[1] idComment
	 * @hash hash sent by the client
   * http://localhost/index.php?comments&command=removeCommentByIdComment&values[]=robert@seederapp.com&values[]=4&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function removeCommentByIdComment($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Check if the comment exists, and that it was publish by the user
		$commentDecoded = json_decode($this->validateCommentByEmail($params[0], $params[1]), true);
		$commentExists = $commentDecoded[0][0][0];
		if ($commentExists == 0){
			return "Comment does not exist, or user did not publish this comment";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
    $this->db->prepare("DELETE FROM User_Comment WHERE idComment = ".$params[1].";");
    if ($this->db->query() != 1){
			return false;
		}
    
		$this->db->prepare("DELETE FROM Comment WHERE idComment = ".$params[1].";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Comment was successfully removed
			return true;
		} else {
			//Failure to remove comment
			return false;
		}
	}

	//http://localhost/index.php?comments&command=getCommentsByIdIdea&values[]=1
	public function getCommentsByIdIdea($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT Comment.*, User.firstName, User.lastName, User.isPrivate FROM Comment INNER JOIN Idea_Comment INNER JOIN User_Comment INNER JOIN User ON Comment.idComment = Idea_Comment.idComment AND Idea_Comment.idIdea = ".$params[0]." AND User_Comment.idComment = Comment.idComment AND User.idUser = User_Comment.idUser ORDER BY Comment.date");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>