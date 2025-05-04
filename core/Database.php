<?php

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;
    
    /**
     * Constructor - Connect to the database
     */
    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
        
        // Set character set
        $this->conn->set_charset('utf8mb4');
    }
    
    /**
     * Query the database
     * 
     * @param string $sql The SQL query
     * @return mysqli_result|bool Query result
     */
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    /**
     * Get result as an array of objects
     * 
     * @param string $sql The SQL query
     * @return array Result array
     */
    public function resultSet($sql) {
        $result = $this->query($sql);
        $rows = [];
        
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }
        
        return $rows;
    }
    
    /**
     * Get single record as object
     * 
     * @param string $sql The SQL query
     * @return object|null Single record
     */
    public function single($sql) {
        $result = $this->query($sql);
        return $result->fetch_object();
    }
    
    /**
     * Get row count
     * 
     * @param string $sql The SQL query
     * @return int Number of rows
     */
    public function rowCount($sql) {
        $result = $this->query($sql);
        return $result->num_rows;
    }
    
    /**
     * Prepare statement with query
     * 
     * @param string $sql The SQL query
     * @return mysqli_stmt Prepared statement
     */
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    /**
     * Get last inserted ID
     * 
     * @return int Last inserted ID
     */
    public function lastInsertId() {
        return $this->conn->insert_id;
    }
    
    /**
     * Get MySQL error
     * 
     * @return string Error message
     */
    public function error() {
        return $this->conn->error;
    }
}
