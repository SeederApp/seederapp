<?php
/**
 * This file handles the retrieval and serving of ideas
 */
class Ideas_Controller {
	/**
	 * This template variable will hold the 'view' portion of the MVC 
	 * for this controller
	 */
	public $template = 'ideas';
	private $method;

	function __construct($method) {
		$this->method = $method;
	}

	/**
	 * @param array $getVars the GET variables posted to index.php
	 */
	public function main(array $getVars) {
		$ideasModel = new Ideas_Model;
		$command = $getVars['command'];
		$values = isset($_GET['values']) ? $_GET['values'] : null;
		if ($this->method == "GET") {
			if (isset($values)){
				$ideas = $ideasModel->$command($_GET['values']);
			}
			else {
				$ideas = $ideasModel->$command();
			}
		}
		else if ($this->method == "POST"){
			$ideas="post";
		}
		print_r($ideas);
	}
}
?>