<?php
/**
 * The MySQL Improved driver extends the Database_Library to provide
 * interaction with a MySQL database
 */
class Mysql_Driver extends Database_Library {
	/**
	 * Connection holds MySQLi resource
	 */
	private $connection;

	/**
	 * Query to perform
	 */
	private $query;

	/**
	 * Result holds data retrieved from server
	 */
	private $result;

	 /**
	 * Db holds name of database to connect to
	 */
	private $db;

	/**
	 * Create new connection to database
	 */
	public function connect() {
		$host="127.0.0.1";
		$port=10000;
		$socket="";
		$user="uOFTUsTfGuI0T";
		$password="pM3DDWbHcxHKW";
		$this->db="d0f0bbda4303c49268209e2ef4b26e8cb";
		$dbname="d0f0bbda4303c49268209e2ef4b26e8cb";
		$this->connection = mysqli_connect($host, $user, $password, $dbname, $port)
			or die ('Could not connect to the database server' . mysqli_connect_error());
	}

	/**
	 * Break connection to database
	 */
	public function disconnect() {
		//Clean up connection!
		$this->connection->close();
		
		return TRUE;
	}

	/**
	 * Prepare query to execute
	 * @param $query
	 */
	public function prepare($query) {
		//Store query in query variable
		$this->query = $query;
		return TRUE;
	}

	/**
	 * Execute a prepared query
	 */
	public function query() {
		if (isset($this->query)) {
			//Execute prepared query and store in result variable
			$db_selected = mysqli_select_db($this->connection, $this->db);
			$this->result = mysqli_query($this->connection, $this->query);
			
			if($this->result === FALSE) {
				die(mysql_error()); // TODO: better error handling
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Fetch a row from the query result
	 * @param $type
	 */
	public function fetch($type = 'object') {
		if (isset($this->result)) {
			switch ($type) {
				case 'array':
					//Fetch a row as array
					//$row = mysql_fetch_array($this->result);
					$rows = array();
					while($r = mysqli_fetch_array($this->result)) {
						$rows[] = array($r);
					}
					break;
					
				case 'object':
					//Fall through...
					
				default:
					//Fetch a row as object
					$row = $this->result->fetch_object();
					break;
			}
			return json_encode($rows);
		}
		return FALSE;
	}

	/**
	 * Sanitize data to be used in a query
	 * @param $data to be sanitized
	 */
	public function escape($data) {
		return $this->connection->real_escape_string($data);
	}
}
?>