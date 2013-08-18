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
	 * @params[0] email
	 * @params[0] firstName
	 * @params[0] lastName
	 * @params[0] gender
	 * @params[0] salt
	 * @params[0] hash
	 * @params[0] photoURL
	 * @params[0] coins
	 * @hash hash sent by the client
	 */
	public function addUser($params, $hash) {
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("INSERT INTO USER (email, firstName, lastName, gender, salt, hash, photoURL, coins) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '".$params[7]."');");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
		
		//Prepare query
		$this->db->prepare("INSERT INTO USER (email, firstName, lastName, gender, salt, hash, photoURL, coins) VALUES ('".$params[0]."', '".$params[1]."', '".$params[2]."', '".$params[3]."', '".$params[4]."', '".$params[5]."', '".$params[6]."', '".$params[7]."');");
		
		//Execute query and return "true" or "false"
		return $this->db->query();
	}


	/*
	 * @params[0] email
	 * @hash hash sent by the client
	 */
	public function deleteUser($params, $hash) {
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
		$userHash = $this->getHashById($params[0]);
		$decodedHash = json_decode($userHash, true);
		
		//Check if the sent client-side hash is different than the one in the database
		if ($decodedHash[0][0][0] != $hash){
			return;
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
		$userHash = $this->getHashByEmail($params[0]);
		$decodedHash = json_decode($userHash, true);
		
		//Check if the sent client-side hash is different than the one in the database
		if ($decodedHash[0][0][0] != $hash){
			return;
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
		$userHash = $this->getHashById($params[0]);
		$decodedHash = json_decode($userHash, true);
		
		//Check if the sent client-side hash is different than the one in the database
		if ($decodedHash[0][0][0] != $hash){
			return;
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
		$userHash = $this->getHashByEmail($params[0]);
		$decodedHash = json_decode($userHash, true);
		
		//Check if the sent client-side hash is different than the one in the database
		if ($decodedHash[0][0][0] != $hash){
			return;
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