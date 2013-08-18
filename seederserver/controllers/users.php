<?php
/**
 * This file handles the retrieval and serving of users
 */
class Users_Controller {
	/**
	 * This template variable will hold the 'view' portion of the MVC 
	 * for this controller
	 */
	public $template = 'users';
	private $method;

	function __construct($method) {
		$this->method = $method;
	}
    
    /**
     * @param array $getVars the GET variables posted to index.php
     */
	public function main(array $getVars) {
		$usersModel = new Users_Model;
		$command = $getVars['command'];
		$values = isset($_GET['values']) ? $_GET['values'] : null;
        if ($this->method == "GET") {
			if (isset($values)){
				$users = $usersModel->$command($_GET['values']);
			}
			else {
				$users = $usersModel->$command();
			}
		}
        else if ($this->method == "POST"){
			$users="post";
		}
		print_r($users);
	}
}
?>