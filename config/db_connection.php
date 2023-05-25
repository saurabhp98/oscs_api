
<?php 
  class Database {
    // DB Params
    private $host = '217.21.84.154';
    private $db_name = 'u506543126_oscs_prod';
    private $username = 'u506543126_oscs_prod';
    private $password = 'Saurabh@123';
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
      
      } catch(Exception $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }