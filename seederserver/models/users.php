<?php
/**
 * The Users Model does the back-end heavy lifting for the Users Controller
 */
class Users_Model{
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
	private function validateEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT count(1) FROM User WHERE email = '".$email."';");
		
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
	public function getSalt($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT salt FROM User WHERE email = '".$email."';");
		
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
	public function getSaltByEmail($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT salt FROM User WHERE email = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @idUser idUser
	 */
	private function getHashById($idUser){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT hash FROM User WHERE idUser = '".$idUser."';");
		
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
	 * @email email
	 */
	private function getLastLoggedInByEmail($email){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT lastLoggedIn FROM User WHERE email = '".$email."';");
		
		//Execute query
		$this->db->query();
	
		//Fetch query
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @email[0] email
	 * return "User already exists", or "true" for successfully inserted, or "false" when an updating error occurs
	 */
	private function generateLoginCoins($email){
		//Get lastLoggedIn
		$lastLoggedInDecoded = json_decode($this->getLastLoggedInByEmail($email), true);
		$lastLoggedIn = $lastLoggedInDecoded[0][0][0];
		$oldDate = (explode(' ', $lastLoggedIn));
		$currentDate = time();
		$oldDate = strtotime($oldDate[0]);
		$dateDiff = floor((abs($currentDate - $oldDate))/(60*60*24));
		if ($dateDiff >= 1){
		
			//Get coins
			$coinsDecoded = json_decode($this->getUserCoinsByEmail($email), true);
			$coins = $coinsDecoded[0][0][0];
			//Connect to database
			$this->db->connect();
			
			//Update User Coins, so user gets a coin back
			$this->db->prepare("UPDATE User SET coins = ".++$coins." WHERE email = '".$email."';");
			
			//Execute query and return "true" or "false"
			$this->db->query();
		}
	}

	/*
	 * @email[0] email
	 * return "User already exists", or "true" for successfully inserted, or "false" when an updating error occurs
	 */
	private function updateLastLoggedIn($email){
		$this->generateLoginCoins($email);
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET lastLoggedIn = now() WHERE email = '".$email."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
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
			$this->updateLastLoggedIn($email);
			return true;
		} else{
			return false;
		}
	}
	
		/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function login($params, $hash){
		$userHash = $this->getHashByEmail($params[0]);
		$userHashDecoded = json_decode($userHash, true);
		if ($userHashDecoded != null){
			if ($userHashDecoded[0][0][0] != $hash){
				return false;
			}
			$this->updateLastLoggedIn($params[0]);
			return true;
		} else{
			return false;
		}
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] salt
	 * @params[5] hash
	 * @params[6] photoURL
	 * @params[7] photoURL
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * index.php?users&command=addUser&values[]=test@seederapp.com&values[]=Some&values[]=Dude&values[]=male&values[]=53452vsd2&values[]=fspl2023dsla432&values[]=sdsds
	 */
	public function addUser($params){
		//Check if user exists
		$existsDecoded = json_decode($this->validateEmail($params[0]), true);
		$exists = $existsDecoded[0][0][0];
		if ($exists != 0){
			return "User already exists";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO User (email, firstName, lastName, gender, salt, hash, photoURL, coins, isPrivate, lastLoggedIn) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5', '".$params[7]."', now());");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] photoURL
	 * @params[5] isPrivate
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=updateUser&values[]=robert@seederapp.com&values[]=Robert&values[]=Stanica&values[]=male&values[]=photo&values[]=false&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function updateUser($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET firstName = '".$params[1]."', lastName = '".$params[2]."', gender = '".$params[3]."', photoURL = '".$params[4]."', isPrivate = '".$params[5]."' WHERE email = '".$params[0]."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] salt
	 * @params[5] hash
	 * @params[6] photoURL
	 * @params[7] isPrivate
	 * @params[8] vendorId
	 * @params[9] website
	 * @params[10] twitter
	 * @params[11] facebook
	 * @params[12] linkedin
	 * @params[13] github
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=addDeveloper&values[]=dev2@seederapp.com&values[]=Some&values[]=Dude&values[]=male&values[]=53452vsd2&values[]=fspl2023dsla432&values[]=sdsds&values[]=222&values[]=true&values[]=http://www.google.com&values[]=twitter&values[]=facebook&values[]=linkedin&values[]=github
	 */
	public function addDeveloper($params){
		//Check if user exists
		$existsDecoded = json_decode($this->validateEmail($params[0]), true);
		$exists = $existsDecoded[0][0][0];
		if ($exists != 0){
			return "User already exists";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query for inserting Developer info
		$this->db->prepare("INSERT INTO Developer (vendorId, website, twitter, facebook, linkedin, github) VALUES ('".$params[8]."', '".$params[9]."', '".$params[10]."', '".$params[11]."', '".$params[12]."', '".$params[13]."');");
		
		if ($this->db->query() == 1){
			//Prepare query for inserting User info
			$this->db->prepare("INSERT INTO User (email, firstName, lastName, gender, salt, hash, photoURL, coins, isDeveloper, idDeveloper, isPrivate, lastLoggedIn) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5', '1', '".$this->db->fetchId()."', '".$params[7]."', now());");
			return $this->db->query();
		}
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] photoURL
	 * @params[6] vendorId
	 * @params[7] vendorId
	 * @params[8] website
	 * @params[9] twitter
	 * @params[10] facebook
	 * @params[11] linkedin
	 * @params[12] github
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=updateDeveloper&values[]=robert@seederapp.com&values[]=Robert&values[]=Stanica&values[]=male&values[]=photo&values[]=1111&values[]=false&values[]=http://www.google.com&values[]=twitter&values[]=facebook&values[]=linkedin&values[]=github&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function updateDeveloper($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		$developerIdDecoded = json_decode($this->getDeveloperIdByEmail($params[0]), true);
		$developerId = $developerIdDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query for inserting Developer info
		$this->db->prepare("UPDATE Developer SET vendorId = '".$params[6]."', website = '".$params[7]."', twitter = '".$params[8]."', facebook = '".$params[9]."', linkedin = '".$params[10]."', github = '".$params[11]."' WHERE idDeveloper = '".$developerId."';");
		
		if ($this->db->query() == 1){
			//Prepare query for inserting User info
			$this->db->prepare("UPDATE User SET firstName = '".$params[1]."', lastName = '".$params[2]."', gender = '".$params[3]."', photoURL = '".$params[4]."', isPrivate = '".$params[5]."' WHERE email = '".$params[0]."';");
			return $this->db->query();
		}
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function removeUser($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		//Get idUser by email
		$idUserDecoded = json_decode($this->getUserIdByEmail($params[0]), true);
		$idUser = $idUserDecoded[0][0][0];
		
		//Delete user's comments
		$this->removeAllUserComments($idUser);
		
		//Delete user's ideas
		$this->removeAllUserIdeas($idUser);
		
		//Delete user's reports
		$this->removeAllUserReports($idUser);
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM User WHERE idUser = ".$idUser.";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//User was successfully removed
			return true;
		} else {
			//Failure to remove user
			return false;
		}
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function removeDeveloper($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		//Get idUser by email
		$idUserDecoded = json_decode($this->getUserIdByEmail($email), true);
		$idUser = $idUserDecoded[0][0][0];
		
		//Get idDeveloper by email
		$idDeveloperDecoded = json_decode($this->getDeveloperIdByEmail($params[0]), true);
		$idDeveloper = $idDeveloperDecoded[0][0][0];
		
		//Remove Developer_Idea records
		$this->removeAllTakenIdeas($idDeveloper);
		
		//Remove developer's comments
		$this->removeAllUserComments($idUser);
		
		//Remove developer's ideas
		$this->removeAllUserIdeas($idUser);
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Developer WHERE idDeveloper = ".$idDeveloper.";");
		
		//Execute query
		if ($this->db->query() == 1){
			//Prepare query
			$this->db->prepare("DELETE FROM User WHERE idUser = ".$idUser.";");
			
			//Execute query and return "true" or "false"
			if ($this->db->query() == 1){
				//User was successfully removed
				return true;
			} else {
				//Failure to remove from User table
				return false;
			}
		} else {
			//Failure to remove from Developer table
			return false;
		}
	}

	/*
	 * @idDeveloper idDeveloper
	 */
	private function removeAllTakenIdeas($idDeveloper){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Developer_Idea WHERE idDeveloper = ".$idDeveloper.";");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @idIdea idIdea
	 */
	private function removeVotesByIdIdea($idIdea){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM VotedIdeas WHERE idIdea = ".$idIdea.";");
		
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
		$this->db->prepare("DELETE FROM ReportedIdeas WHERE idIdea = ".$idIdea.";");
		
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
		$this->db->prepare("DELETE FROM Developer_Idea WHERE idIdea = ".$idIdea.";");
		
		//Execute query
		$this->db->query();
	}

	private function getAllIdeaIdsByIdUser($idUser){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idIdea FROM Idea WHERE idUser = ".$idUser.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
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

	private function getAllCommentIdsByIdUser($idUser){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idComment FROM User_Comment WHERE idUser = ".$idUser.";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @idIdea idIdea
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
	 * @idUser idUser
	 */
	private function removeUserCommentByIdUser($idUser){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM User_Comment WHERE idUser = ".$idUser.";");
		
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
	private function removeCommentsByIdComment($idComment){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM Comment WHERE idComment = ".$idComment.";");
		
		//Execute query
		$this->db->query();
	}

	/*
	 * @email email
	 */
	public function getUserCoinsByEmail($email){
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
	 * @email email
	 */
	private function getUserCoinsByIdUser($idUser){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT coins FROM User WHERE idUser = ".$idUser.";");
		
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
	 * @idUser idUser
	 * @idIdea idIdea
	 * return "true" for successfully removed, or "false" when a removing error occurs
	 */
	private function removeUserIdea($idUser, $idIdea){
		//Remove all votes for the idea
		$this->removeVotesByIdIdea($idIdea);
		
		//Remove all reports for the idea
		$this->removeReportsByIdIdea($idIdea);
		
		//Remove all developer associations by deleting Developer_Idea records
		$this->removeDeveloperAssociationsByIdIdea($idIdea);
		
		//Get all comment ids
		$commentIdsDecoded = json_decode($this->getAllCommentIdsByIdIdea($idIdea), true);
		$totalLength = count($commentIdsDecoded);
		
		//Delete all Idea_Commment records by the idIdea
		$this->removeIdeaCommentByIdIdea($idIdea);
		
		for ($i = 0; $i < $totalLength; $i++){
			//Delete from User_Comments the records with the idComments
			$this->removeUserCommentByIdComment($commentIdsDecoded[$i][0][0]);
			//Delete from Comment the records with the idComments
			$this->removeCommentsByIdComment($commentIdsDecoded[$i][0][0]);
		}
		
		//Get coins
		$coinsDecoded = json_decode($this->getUserCoinsByIdUser($idUser), true);
		$coins = $coinsDecoded[0][0][0];
		
		//Connect to database
		$this->db->connect();
		
		//Update User Coins, so user gets a coin back
		$this->db->prepare("UPDATE User SET coins = ".++$coins." WHERE idUser = ".$idUser.";");
		
		//Execute query and return "true" or "false"
		if ($this->db->query() == 1){
			//Prepare query
			$this->db->prepare("DELETE FROM Idea WHERE idUser = ".$idUser." AND idIdea = ".$idIdea.";");
			
			//Execute query and return "true" or "false"
			return $this->db->query();
		} else {
			return "Failure to update user's coins number";
		}
	}

	/*
	 * @idUser idUser
	 * @idIdea idIdea
	 * return "true" for successfully removed, or "false" when a removing error occurs
	 */
	private function removeAllUserIdeas($idUser){
		//Get all idea ids to be removed
		$ideaIdsDecoded = json_decode($this->getAllIdeaIdsByIdUser($idUser), true);
		$totalLength = count($ideaIdsDecoded);
		
		//Delete user's ideas
		for ($i = 0; $i < $totalLength; $i++){
			$this->removeUserIdea($idUser, $ideaIdsDecoded[$i][0][0]);
		}
	}

	/*
	 * @idUser idUser
	 * return "true" for successfully removed, or "false" when a removing error occurs
	 */
	private function removeAllUserComments($idUser){
		//Get all comment ids to be removed
		$commentIdsDecoded = json_decode($this->getAllCommentIdsByIdUser($idUser), true);
		$totalLength = count($commentIdsDecoded);
		
		//Delete all User_Commment records by the idUser
		$this->removeUserCommentByIdUser($idUser);
		
		for ($i = 0; $i < $totalLength; $i++){
			//Delete from User_Comments the records with the idComments
			$this->removeUserCommentByIdComment($commentIdsDecoded[$i][0][0]);
			//Delete from Comment the records with the idComments
			$this->removeCommentsByIdComment($commentIdsDecoded[$i][0][0]);
		}
	}

	/*
	 * @idUser idUser
	 * return "true" for successfully removed, or "false" when a removing error occurs
	 */
	private function removeAllUserReports($idUser){
		//Delete all ReportedIdea records by the idUser
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM ReportedIdeas WHERE idUser = '".$idUser."';");
		
	return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function setIsBanned($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET isBanned = '1' WHERE email = '".$params[0]."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function setUnbanned($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET isBanned = '0' WHERE email = '".$params[0]."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function setIsAdmin($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET isAdmin = '1' WHERE email = '".$params[0]."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function setIsNotAdmin($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET isAdmin = '0' WHERE email = '".$params[0]."';");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 */
	public function isDeveloper($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT isDeveloper FROM User WHERE email = '".$params[0]."';");
		
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
	public function isAdmin($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT isAdmin FROM User WHERE email = '".$params[0]."';");
		
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
	public function isBanned($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT isBanned FROM User WHERE email = '".$params[0]."';");
		
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
	public function isPrivate($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT isPrivate FROM User WHERE email = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] idUser
	 * @hash hash sent by the client
	 */
	public function getUserById($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins, isPrivate FROM User WHERE idUser = '".$params[1]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function getUserByEmail($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins, isPrivate, isDeveloper FROM User WHERE email = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
 	 * @params[0] idDeveloper
	 * @hash hash sent by the client
	 */
	public function getDeveloperById($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT User.email, User.firstName, User.lastName, User.gender, User.photoURL, User.coins, User.isPrivate, Developer.* FROM User JOIN Developer WHERE User.idDeveloper = Developer.idDeveloper AND Developer.idDeveloper = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function getDeveloperByEmail($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT User.email, User.firstName, User.lastName, User.gender, User.photoURL, User.coins, User.isPrivate, Developer.* FROM User JOIN Developer WHERE User.idDeveloper = Developer.idDeveloper AND User.email = '".$params[0]."';");
		
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
	public function sendMail($email, $key){

		include_once (SERVER_ROOT . '/libraries/sendgrid-php/SendGrid_loader.php');
		$sendgrid = new SendGrid(getenv('SENDGRID_USERNAME'), getenv('SENDGRID_PASSWORD'));
		$mail = new SendGrid\Mail();
		$mail->
		  addTo($email)->
		  setFrom('seederapp@gmail.com')->
		  setSubject('Password recovery service')->
		  setHtml('Your key is: <strong>'.$key.'</strong>. Please enter it on your BlackBerry device.');
		  $sendgrid-> web-> send($mail);
	}
	
	/*
	 * @params[0] email
	 */
	public function getUserPasswordKey($params){
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT passwordKey FROM User WHERE email = '".$params[0]."';");
		
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
	public function createUserPasswordKey($params){
		
		$key = rand(pow(10, 4), pow(10, 5)-1);
		$this -> sendMail($params[0], $key);
		//Connect to database
		$this->db->connect();
		//Prepare query
		$this->db->prepare("UPDATE User SET passwordKey = '".$key."' WHERE email = '".$params[0]."';");
		
		//Execute query
		return $this->db->query();
	}
	
	private function resetUserPasswordKey($email){

		//Connect to database
		$this->db->connect();
		//Prepare query
		$this->db->prepare("UPDATE User SET passwordKey = 'NULL' WHERE email = '".$email."';");
		
		//Execute query
		return $this->db->query();
	}
	
	/*
	 * @params[0] email
	 * @params[1] salt
	 * @params[2] hash
	 * @params[3] passwordKey
	 */
	public function changeUserPassword($params){
		
		$userPasswordKeyDecoded = json_decode($this->getUserPasswordKey($params), true);
		$userPasswordKey = $userPasswordKeyDecoded[0][0][0];
		if (($userPasswordKey != $params[3]) || $userPasswordKey == 'NULL')
			return "Invalid password key";
	
		//Connect to database
		$this->db->connect();
		//Prepare query
		$this->db->prepare("UPDATE User SET salt = '".$params[1]."', hash = '".$params[2]."' WHERE email = '".$params[0]."';");
		
		//Execute query
		if ($this->db->query())
			return $this->resetUserPasswordKey($params[0]);
		else
			return "Failed to update user";
	}
}
?>