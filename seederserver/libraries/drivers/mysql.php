<?php
/**
 * The MySQL Improved driver extends the Database_Library to provide 
 * interaction with a MySQL database
 */
class Mysql_Driver extends Database_Library
{
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
    public function connect()
    {
        $services_json = json_decode(getenv("VCAP_SERVICES"),true);
        $mysql_config = $services_json["mysql-5.1"][0]["credentials"];
        //connection parameters
        $username = $mysql_config["username"];
        $password = $mysql_config["password"];
        $hostname = $mysql_config["hostname"];
        $port = $mysql_config["port"];
        $this->db = $mysql_config["name"]; 
        //create new mysql connection
        $this->connection = mysql_connect("$hostname:$port", $username, $password);
        if (!$this->connection) {
          die('Could not connect: ' . mysql_error());
          return FALSE;
        }
        else
          return TRUE;
    }

    /**
     * Break connection to database
     */
    public function disconnect()
    {
        //clean up connection!
        $this->connection->close();    
    
        return TRUE;
    }

    /**
     * Prepare query to execute
     * 
     * @param $query
     */
    public function prepare($query)
    {
        //store query in query variable
        $this->query = $query;    
    
        return TRUE;
    }

    /**
     * Execute a prepared query
     */
    public function query()
    {
        if (isset($this->query))
        {
            //execute prepared query and store in result variable
            $db_selected = mysql_select_db($this->db, $this->connection);
            $this->result = mysql_query($this->query);
            if($this->result === FALSE) {
              die(mysql_error()); // TODO: better error handling
            }
            return TRUE;
        }
    
        return FALSE;        
    }

    /**
     * Fetch a row from the query result
     * 
     * @param $type
     */
    public function fetch($type = 'object')
    {
        if (isset($this->result))
        {
            switch ($type)
            {
                case 'array':
                    //fetch a row as array
                    //$row = mysql_fetch_array($this->result);
                    $rows = array();
                    while($r = mysql_fetch_array($this->result)) { 
                        $rows[] = array($r);
                    }
                break;
            
                case 'object':
            
                //fall through...
            
                default:
                
                    //fetch a row as object
                    $row = $this->result->fetch_object();    
                    
                break;
            }
        
            return json_encode($rows);
        }
    
        return FALSE;
    }
    
     /**
     * Sanitize data to be used in a query
     * 
     * @param $data
     */
    public function escape($data)
    {
        return $this->connection->real_escape_string($data);
    }
}