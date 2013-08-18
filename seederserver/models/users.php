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

	/*
	 * @email email
	 */
	public function getSalt($email) {
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
	private function getHashById($idUser) {
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
	 * @email email
	 */
	private function getIdByEmail($email) {
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
	 * @params[1] firstName
	 * @params[2] lastName
	 * @params[3] gender
	 * @params[4] salt
	 * @params[5] hash
	 * @params[6] photoURL
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function addUser($params, $hash) {
		//Check if user exists
		if (validateEmail($params[0])){
			return "User already exists";
		}
		
		//Connect to database
		$this->db->connect();
				
		//Prepare query
		$this->db->prepare("INSERT INTO USER (email, firstName, lastName, gender, salt, hash, photoURL, coins) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5');");
		
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
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function addDeveloper($params, $hash) {
		//Check if user exists
		if (validateEmail($params[0])){
			return "User already exists";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query for inserting Developer info
		$this->db->prepare("INSERT INTO Developer (vendorId, twitter, facebook, lindedin, github) VALUES ('".$params[7]."', '".$params[8]."', '".$params[9]."', '".$params[10]."', '".$params[11]."');");
		
		if ($this->db->query()){		
			//Prepare query for inserting User info
			$this->db->prepare("INSERT INTO User (email, firstName, lastName, gender, salt, hash, photoURL, coins, isDeveloper, idDeveloper) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '5', '1', '".$this->db->insert_id."');");
			return $this->db->query();
		}
	}


	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function deleteUser($params, $hash) {
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("DELETE FROM User WHERE email = '".$params[0]."';");
		
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
	public function isDeveloper($params) {
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
	public function isAdmin($params) {
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
	public function isBanned($params) {
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
	public function getUserById($params, $hash) {
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
	public function getUserByEmail($params, $hash) {
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
	public function getDeveloperById($params, $hash) {
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
	public function getDeveloperByEmail($params, $hash) {
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