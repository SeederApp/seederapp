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
	 * @params[5] idDeveloper
	 * @params[6] vendorId
	 * @params[7] twitter
	 * @params[8] facebook
	 * @params[9] linkedin
	 * @params[10] github
	 * @hash hash sent by the client
	 * return "User already exists", or "true" for successfully inserted, or "false" when an inserting error occurs
	 */
	public function updateDeveloper($params, $hash){
		//Authenticate user
		if (!$this->authenticateUser($params[0], $hash)){
			return "Invalid user or password";
		}
		
		//Connect to database
		$this->db->connect();
		
		//Prepare query for inserting Developer info
		$this->db->prepare("UPDATE Developer SET vendorId = '".$params[6]."', twitter = '".$params[7]."', facebook = '".$params[8]."', linkedin = '".$params[9]."', github = '".$params[10]."' WHERE idDeveloper = '".$params[5]."';");
		
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
	public function deleteUser($params, $hash){
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