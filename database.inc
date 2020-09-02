<?php
class Database{
 
    // specify your own database credentials
    private $host="127.0.0.1";
	private $db_name="check-in";
    private $username="root";
    private $password="";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        // Reset conn så den er null
        $this->conn = null;
 
        // Prøv og forbind til sql databasen
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        // Ellers ved fejl, echo fejlen
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        // Returner conn variablen
        return $this->conn;
    }
}
?>