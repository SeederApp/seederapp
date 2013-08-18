<?php
	function __autoload($className) {
		//Parse out filename where class should be located
		//This supports names like 'Example_Model' as well as 'Example_Two_Model'
		list($suffix, $filename) = preg_split('/_/', strrev($className), 2);
		$filename = strrev($filename);
		$suffix = strrev($suffix);
	
		//Select the folder where class should be located based on suffix
		switch (strtolower($suffix)) {
			case 'model':
				$folder = '/models/';
				break;
			case 'library':
				$folder = '/libraries/';
				break;
			
			case 'driver':
				$folder = '/libraries/drivers/';
				break;
		}

		//Compose file name
		$file = SERVER_ROOT . $folder . strtolower($filename) . '.php';
		
		//Fetch file
		if (file_exists($file)) {
			//Get file
			include_once($file);
		} else {
			//File does not exist!
			die("File '$filename' containing class '$className' not found in '$folder'.");    
		}
	}

	//Fetch the passed request
	$request = $_SERVER['QUERY_STRING'];

	//Parse the page request and other GET variables
	$parsed = explode('&' , $request);

	//The page is the first element
	$page = array_shift($parsed);

	//The rest of the array are get statements, parse them out
	$getVars = array();
	foreach ($parsed as $argument) {
		//Explode GET vars along '=' symbol to separate variable and values
		list($variable , $value) = explode('=' , $argument);
		$getVars[$variable] = urldecode($value);
	}

	//Compute the path to the file
	$target = SERVER_ROOT . '/controllers/' . $page . '.php';

	//Get target
	if (file_exists($target)) {
		include_once($target);
		
		//Modify page to fit naming convention
		$class = ucfirst($page) . '_Controller';
		
		//Instantiate the appropriate class
		if (class_exists($class)) {
			//Get HTTP request method and pass it to controller
			$method = $_SERVER['REQUEST_METHOD'];
			$controller = new $class($method);
		} else {
			//Did we name our class correctly?
			die('class does not exist!');
		}
	} else {
		//Can not find the file in 'controllers'! 
		die('page does not exist!');
	}

	//Once we have the controller instantiated, execute the default function and
	//pass any GET variables to the main method
	$controller->main($getVars);
?>