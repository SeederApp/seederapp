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

	public function getCommentsByIdIdea($params) {
		//Connect to database
		$this->db->connect();
		
		//Prepare query
		$this->db->prepare("SELECT Comment.* FROM Comment JOIN Idea_Comment WHERE Comment.idComment = Idea_Comment.idComment AND Idea_Comment.idIdea = ".$params['0'].";");
		
		//Execute query
		$this->db->query();
		
		//Fetch data
		$article = $this->db->fetch('array');
		
		//Return data
		return $article;
	}
}
?>