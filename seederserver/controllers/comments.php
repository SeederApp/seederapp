<?php
/**
 * This file handles the retrieval and serving of comments
 */
class Comments_Controller {
	/**
	 * This template variable will hold the 'view' portion of the MVC 
	 * for this controller
	 */
	public $template = 'comments';
	private $method;

	function __construct($method) {
		$this->method = $method;
	}

	/**
	 * @param array $getVars the GET variables posted to index.php
	 */
	public function main(array $getVars) {
		$commentsModel = new Comments_Model;
		$command = $getVars['command'];
		$values = isset($_GET['values']) ? $_GET['values'] : null;
		if ($this->method == "GET") {
			if (isset($values)){
				$comments = $commentsModel->$command($_GET['values']);
			}
			else {
				$comments = $commentsModel->$command();
			}
		}
		else if ($this->method == "POST"){
			$comments="post";
		}
		print_r($comments);
	}
}
?>