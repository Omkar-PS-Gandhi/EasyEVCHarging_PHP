<?php

class Database {
    private $connection;

    public function __construct() {
        // Connect on creation
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("DB Connection failed: " . $this->connection->connect_error);
        }
        // Ensure UTF8
        $this->connection->set_charset('utf8mb4');
    }

    // Prepare and return a statement
    public function prepare($sql) {
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $this->connection->error);
        }
        return $stmt;
    }

    // Execute a query directly (use for simple SELECTs)
    public function query($sql) {
        $result = $this->connection->query($sql);
        if (!$result) {
            die("Query failed: " . $this->connection->error);
        }
        return $result;
    }

    // Get last insert ID
    public function insert_id() {
        return $this->connection->insert_id;
    }
}
?>
