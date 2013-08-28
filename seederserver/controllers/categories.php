<?php
/**
 * This file handles the retrieval and serving of categories
 */
class Categories_Controller {
	/**
	 * This template variable will hold the 'view' portion of the MVC 
	 * for this controller
	 */
	public $template = 'categories';
	private $method;

	function __construct($method) {
		$this->method = $method;
	}

	/**
	 * @param array $getVars the GET variables posted to index.php
	 */
	public function main(array $getVars) {
	//hash=80867ff188f6159e110afca6bfe997d1dc436c0552533902552104dda473c00.49723503
		$categoriesModel = new Categories_Model;
		$command = $getVars['command'];
		$hash = isset($getVars['hash']) ? $getVars['hash'] : null;
		$values = isset($_GET['values']) ? $_GET['values'] : null;

	if ($this->method == "GET") {
		if (isset($values) && isset($hash)){
			$ideas = $categoriesModel->$command($values, $hash);
		}
		else if (isset($values)) {
			$ideas = $categoriesModel->$command($values);
		}
	else
		$ideas = $categoriesModel->$command();
	}
	else if ($this->method == "POST")
		$ideas="post";
		print_r($ideas);
	}
}
?>