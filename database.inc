<?php
class Database{
 
    // specify your own database credentials
    // private $host="127.0.0.1";
	// private $db_name="checkin";
    // private $username="root";
    // private $password="";
    public $conn;

//Azure Test Connection
    private $host="checkin.mysql.database.azure.com";
	private $db_name="check-in";
    private $username="SKPLogin@checkin";
    private $password="Qwerty12";
    private $options = array(
        PDO::MYSQL_ATTR_SSL_CA => "C:\Windows\Cacert\BaltimoreCyberTrustRoot.crt.pem",
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    );
 
    // get the database connection
    public function getConnection(){
 
        // Reset conn så den er null
        $this->conn = null;
 
        // Prøv og forbind til sql databasen
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, $this->options);
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