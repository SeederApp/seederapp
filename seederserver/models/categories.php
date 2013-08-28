<?php
/**
 * The Categories Model does the back-end heavy lifting for the Categories Controller
 */
class Categories_Model {
	/**
	 * Holds instance of database connection
	 */
	private $db;
	
	public function __construct() {
		$this->db = new Mysql_Driver;
	}

	public function getAllCategories() {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT * FROM Category;");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	public function getAllAppCategories() {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idCategory, name FROM Category WHERE categoryType = 'Apps';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	public function getAllGameCategories() {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT idCategory, name FROM Category WHERE categoryType = 'Games';");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	public function getCategoryById($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT * FROM Category WHERE idCategory = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}

	public function getCategoryByName($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT * FROM Category WHERE name = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>