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
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] salt
	 * @params[5] hash
	 * @params[6] photoURL
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
		$this->db->prepare("INSERT INTO User (email, firstName, lastName, gender, salt, hash, photoURL, coins) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5');");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] photoURL
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=updateUser&values[]=robert@seederapp.com&values[]=Robert&values[]=Stanica&values[]=male&values[]=photo&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
	 */
	public function updateUser($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("UPDATE User SET firstName = '".$params[1]."', lastName = '".$params[2]."', gender = '".$params[3]."', photoURL = '".$params[4]."' WHERE email = '".$params[0]."';");
		
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
	 * @params[7] vendorId
	 * @params[8] twitter
	 * @params[9] facebook
	 * @params[10] linkedin
	 * @params[11] github
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=addDeveloper&values[]=dev2@seederapp.com&values[]=Some&values[]=Dude&values[]=male&values[]=53452vsd2&values[]=fspl2023dsla432&values[]=sdsds&values[]=222&values[]=twitter&values[]=facebook&values[]=linkedin&values[]=github
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
		$this->db->prepare("INSERT INTO Developer (vendorId, twitter, facebook, linkedin, github) VALUES ('".$params[7]."', '".$params[8]."', '".$params[9]."', '".$params[10]."', '".$params[11]."');");
		
		if ($this->db->query() == 1){
			//Prepare query for inserting User info
			$this->db->prepare("INSERT INTO User (email, firstName, lastName, gender, salt, hash, photoURL, coins, isDeveloper, idDeveloper) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5', '1', '".$this->db->fetchId()."');");
			return $this->db->query();
		}
	}

	/*
	 * @params[0] email
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] photoURL
	 * @params[5] vendorId
	 * @params[6] twitter
	 * @params[7] facebook
	 * @params[8] linkedin
	 * @params[9] github
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 * http://localhost/index.php?users&command=updateDeveloper&values[]=robert@seederapp.com&values[]=Robert&values[]=Stanica&values[]=male&values[]=photo&values[]=1111&values[]=twitter&values[]=facebook&values[]=linkedin&values[]=github&hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
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
		$this->db->prepare("UPDATE Developer SET vendorId = '".$params[5]."', twitter = '".$params[6]."', facebook = '".$params[7]."', linkedin = '".$params[8]."', github = '".$params[9]."' WHERE idDeveloper = '".$developerId."';");
		
		if ($this->db->query() == 1){
			//Prepare query for inserting User info
			$this->db->prepare("UPDATE User SET firstName = '".$params[1]."', lastName = '".$params[2]."', gender = '".$params[3]."', photoURL = '".$params[4]."' WHERE email = '".$params[0]."';");
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
		$idUserDecoded = json_decode($this->getUserIdByEmail($email), true);
		$idUser = $idUserDecoded[0][0][0];
		
		//Delete user's comments
		$this->removeAllUserComments($idUser);
		
		//Delete user's ideas
		$this->removeAllUserIdeas($idUser);
		
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
	private function removeIdeaCommentByIdIdea($idUser){
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
	 * @hash hash sent by the client
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
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins FROM User WHERE idUser = '".$params[1]."';");
		
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
		$this->db->prepare("SELECT email, firstName, lastName, gender, photoURL, coins FROM User WHERE email = '".$params[0]."';");
		
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
		$this->db->prepare("SELECT User.email, User.firstName, User.lastName, User.gender, User.photoURL, User.coins, Developer.* FROM User JOIN Developer WHERE User.idDeveloper = Developer.idDeveloper AND Developer.idDeveloper = '".$params[0]."';");
		
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
		$this->db->prepare("SELECT User.email, User.firstName, User.lastName, User.gender, User.photoURL, User.coins, Developer.* FROM User JOIN Developer WHERE User.idDeveloper = Developer.idDeveloper AND User.email = '".$params[0]."';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>