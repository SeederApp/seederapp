<?php
/**
 * The Categories Model does the back-end heavy lifting for the Categories Controller
 */
class Uploads_Model {
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

}
?>