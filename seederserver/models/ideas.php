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
	 * @email email
	 */
	private function getDeveloperIdByEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idDeveloper FROM User WHERE email = '".$email."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
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
		
		//Get coins
		$coinsDecoded = json_decode($this->getUserCoinsByEmail($params[0]), true);
		$coins = $coinsDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		if (0 < $coins){
			
			//Update User Coins
			$this->db->prepare("UPDATE User SET coins = ".--$coins." WHERE email = '".$params[0]."';");
			
			//Execute query and return "true" or "false"
			if ($this->db->query() == 1){
				//Get user id by email
				$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
				$userId = $userIdDecoded[0][0][0];
				
				//Prepare query. Idea automatically receives one vote from the user
				$this->db->prepare("INSERT INTO Idea (idUser, idCategory, title, description, date, votes, voteDate) VALUES (".$userId.", ".$params[1].", '".$params[2]."', '".$params[3]."', now(), 1, now());");
				
				//Execute query and return "true" or "false"
				if ($this->db->query() == 1){
					//Prepare query
					$this->db->prepare("INSERT INTO VotedIdea (idUser, idIdea) VALUES (".$userId.", ".$this->db->fetchId().");");
					
					//Execute query and return "true" or "false"
					return $this->db->query();
				} else {
					return "Failure to insert idea";
				}
			} else {
				return "Failure to update user's coins number";
			}
		} else {
			return "User does not have enough coins to vote";
		}
	}

	/*
	 * @email email
	 * @idIdea idIdea
	 */
	public function validateIdeaByEmail($email, $idIdea){
		//Connect to database
		$this->db->connect();
		
		//Get user id by email
		$userIdDecoded = json_decode($this->getUserIdByEmail($email), true);
		$userId = $userIdDecoded[0][0][0];
		
		//Prepare query
		$this->db->prepare("SELECT count(1) FROM Idea WHERE idUser = ".$userId." AND idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully removed, or "false" when a removing error occurs
	 */
	public function removeIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Check if the idea exists, and that it was publish by the user
		$ideaDecoded = json_decode($this->validateIdeaByEmail($params[0], $params[1]), true);
		$ideaExists = $ideaDecoded[0][0][0];
		if ($ideaExists == 0){
			return "Idea does not exist, or user did not publish this idea";
		}
		
		//Remove all votes for the idea
		$this->removeVotesByIdIdea($params[1]);
		
		//Remove all reports for the idea
		$this->removeReportsByIdIdea($params[1]);
		
		//Remove all developer associations by deleting Developer_Idea records
		$this->removeDeveloperAssociationsByIdIdea($params[1]);
		
		//Get all comment ids
		$commentIdsDecoded = json_decode($this->getAllCommentIdsByIdIdea($params[1]), true);
		$totalLength = count($commentIdsDecoded);
		//Delete all Idea_Commment records by the idIdea
		$this->removeIdeaCommentByIdIdea($params[1]);
		
		for ($i = 0; $i < $totalLength; $i++){
			//Delete from User_Comments the records with the idComments
			$this->removeUserCommentByIdComment($commentIdsDecoded[$i][0][0]);
			//Delete from Comment the records with the idComments
			$this->removeCommentsByIdComment($commentIdsDecoded[$i][0][0]);	
		}
		
		//Get coins
		$coinsDecoded = json_decode($this->getUserCoinsByEmail($params[0]), true);
		$coins = $coinsDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Update User Coins, so user gets a coin back
		$this->db->prepare("UPDATE User SET coins = ".++$coins." WHERE email = '".$params[0]."';");
			
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Get user id by email
			$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
			$userId = $userIdDecoded[0][0][0];
			
			//Prepare query
			$this->db->prepare("DELETE FROM Idea WHERE idUser = ".$userId." AND idIdea = ".$params[1].";");
			
			//Execute query and return "true" or "false"
			return $this->db->query();
		} else {
			return "Failure to update user's coins number";
		}
	}

	private function getAllCommentIdsByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idComment FROM Idea_Comment WHERE idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @idComment idComment
	 */
	private function removeCommentsByIdComment($idComment){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Comment WHERE idComment = ".$idComment.";");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @idComment idComment
	 */
	private function removeUserCommentByIdComment($idComment){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM User_Comment WHERE idComment = ".$idComment.";");
		
		//Execute query
		$this->db->query();
	}
	
	/*
	 * @idComment idComment
	 */
	private function removeIdeaCommentByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Idea_Comment WHERE idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function takeIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		$developerIdDecoded = json_decode($this->getDeveloperIdByEmail($params[0]), true);
		$developerId = $developerIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO Developer_Idea (idDeveloper, idIdea, progress) VALUES ('".$developerId."', '".$params[1]."', '0');");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}
	
	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function releaseIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		$developerIdDecoded = json_decode($this->getDeveloperIdByEmail($params[0]), true);
		$developerId = $developerIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Developer_Idea WHERE idDeveloper = ".$developerId." AND idIdea = ".$params[1].";");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
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
	 */
	private function getUserCoinsByEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT coins FROM User WHERE email = '".$email."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @idIdea idIdea
	 */
	public function getVotesByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT votes FROM Idea WHERE idIdea = '".$idIdea."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @idIdea idIdea
	 */
	private function removeVotesByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM VotedIdeas WHERE idIdea = '".$idIdea."';");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @idIdea idIdea
	 */
	private function removeReportsByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM ReportedIdeas WHERE idIdea = '".$idIdea."';");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @idIdea idIdea
	 */
	private function removeDeveloperAssociationsByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Developer_Idea WHERE idIdea = '".$idIdea."';");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @idIdea idIdea
	 */
	public function getReportsByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT votes FROM Idea WHERE idIdea = '".$idIdea."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @email email
	 * @idIdea idIdea
	 */
	public function validateReportingByEmail($email, $idIdea){
		//Connect to database
		$this->db->connect();
		
		//Get user id by email
		$userIdDecoded = json_decode($this->getUserIdByEmail($email), true);
		$userId = $userIdDecoded[0][0][0];

		//Prepare query
		$this->db->prepare("SELECT count(1) FROM ReportedIdeas WHERE idUser = ".$userId." AND idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?ideas&command=reportIdea&values[]=robert@seederapp.com&values[]=1&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function reportIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Check if vote exists on this idea regarding user
		$reportExistsDecoded = json_decode($this->validateReportingByEmail($params[0], $params[1]), true);
		$reportExists = $voteExistsDecoded[0][0][0];
		if ($reportExists != 0){
			return "User already reported this idea";
		}
		
		//Update number of votes
		$reportDecoded = json_decode($this->getReportsByIdIdea($params[1]), true);
		$reports = $votesDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE Idea SET reportNumber = ".++$reports." WHERE idIdea = ".$params[1].";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			
			//Insert ReportIdeas record
			$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
			$userId = $userIdDecoded[0][0][0];
			$this->db->prepare("INSERT INTO ReportIdeas (idIdea, idUser) VALUES (".$params[1].", ".$userId.");");
			
			//Execute query and return "true" or "false"
			return $this->db->query();
		} else {
			return "Failure to update votes number";
		}
	}

	/*
	 * @email email
	 * @idIdea idIdea
	 */
	public function validateVoteByEmail($email, $idIdea){
		//Connect to database
		$this->db->connect();
		
		//Get user id by email
		$userIdDecoded = json_decode($this->getUserIdByEmail($email), true);
		$userId = $userIdDecoded[0][0][0];

		//Prepare query
		$this->db->prepare("SELECT count(1) FROM VotedIdeas WHERE idUser = ".$userId." AND idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?ideas&command=voteOnIdea&values[]=robert@seederapp.com&values[]=1&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function voteOnIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Check if vote exists on this idea regarding user
		$voteExistsDecoded = json_decode($this->validateVoteByEmail($params[0], $params[1]), true);
		$voteExists = $voteExistsDecoded[0][0][0];
		if ($voteExists != 0){
			return "User already voted on this idea";
		}
		
		//Update number of votes
		$votesDecoded = json_decode($this->getVotesByIdIdea($params[1]), true);
		$votes = $votesDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE Idea SET votes = ".++$votes." WHERE idIdea = ".$params[1].";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Insert votedIdea record
			$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
			$userId = $userIdDecoded[0][0][0];
			
			$this->db->prepare("INSERT INTO VotedIdeas (idIdea, idUser) VALUES (".$params[1].", ".$userId.");");
			
			//Execute query and return "true" or "false"
			return $this->db->query();
		} else {
			return "Failure to update votes number";
		}
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?ideas&command=unvoteOnIdea&values[]=robert@seederapp.com&values[]=1&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function removeVoteOnIdea($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Check if vote exists on this idea regarding user
		$voteExistsDecoded = json_decode($this->validateVoteByEmail($params[0], $params[1]), true);
		$voteExists = $voteExistsDecoded[0][0][0];
		if ($voteExists == 0){
			return "User never voted on this idea";
		}
		
		//Update number of votes
		$votesDecoded = json_decode($this->getVotesByIdIdea($params[1]), true);
		$votes = $votesDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE Idea SET votes = ".--$votes." WHERE idIdea = ".$params[1].";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Insert Voted_Idea record
			$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
			$userId = $userIdDecoded[0][0][0];
			
			$this->db->prepare("DELETE FROM VotedIdeas WHERE idIdea = ".$params[1]." AND idUser = ".$userId.";");
			
			//Execute query and return "true" or "false"
			return $this->db->query();
		} else {
			return "Failure to update votes number";
		}
	}

	/*
	 * @params[0] email
	 * @params[1] idIdea
	 * @params[2] progress
	 * @params[3] appId //Only if @params[2] == 100
	 * @hash hash sent by the client
	 * return "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function updateProgress($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		$developerIdDecoded = json_decode($this->getDeveloperIdByEmail($params[0]), true);
		$developerId = $developerIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		if ((100 == $params[2]) && (null != $params[2]) && (null != $params[3])){
			$this->db->prepare("UPDATE Developer_Idea SET progress = '".$params[2]."', appId = '".$params[3]."' WHERE idDeveloper = '".$developerId."' AND idIdea = '".$params[1]."';");
		} else if ((100 > $params[2]) && (0 <= $params[2])){
			$this->db->prepare("UPDATE Developer_Idea SET progress = '".$params[2]."' WHERE idDeveloper = '".$developerId."' AND idIdea = '".$params[1]."';");
		} else {
			return 0;
		}
		
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
	/*
	 * @params[0] email
	 */
	//Get ideas taken by developer
	public function getIdeasTakenByEmail($params){
		
		$userIdDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
		$userId = $userIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT Idea.* FROM Idea INNER JOIN Developer_Idea WHERE Idea.idIdea = Developer_Idea.idIdea AND Idea.idUser = ".$userId.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>
