<?php
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "xcs-ams";
    private $username = "root";
    private $password = "Pa55w0rd#123&";
    public $conn;
	
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
		if(!$this->conn){
			die("Can't connect database");
		}
 
        return $this->conn;
    }
}
?>