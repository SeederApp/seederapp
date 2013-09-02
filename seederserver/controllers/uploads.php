<?php
/**
 * This file handles the retrieval and serving of ideas
 */
class Uploads_Controller {
	/**
	 * This template variable will hold the 'view' portion of the MVC 
	 * for this controller
	 */
	public $template = 'uploads';
	private $method;

	function __construct($method) {
		$this->method = $method;
	}

	/**
	 * @param array $getVars the GET variables posted to index.php
	 */
	public function main(array $getVars) {
		$uploadsModel = new Uploads_Model;
		$command = $getVars['command'];
		$hash = isset($getVars['hash']) ? $getVars['hash'] : null;
		$values = isset($_GET['values']) ? $_GET['values'] : null;

	if ($this->method == "POST") {
		if (isset($values) && isset($hash)){
			$ideas = $uploadsModel->$command($values, $hash);
		}
		else if (isset($values)) {
			$ideas = $uploadsModel->$command($values);
		}
	else
		$ideas = $uploadsModel->$command();
	}
	else if ($this->method == "GET")
		$ideas="get";
		print_r($ideas);
	}
}
?>