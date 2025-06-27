<?php

class User {
    private $connection;

    public function __construct(Database $database) {
        $this->connection = $database;
    }

    // Registers a new user account (admin or standard user)
    public function registerUser($fullName, $mobile, $emailAddress, $rawPassword, $role = 'user') {
        // Step 1: Validate user role
        if (!in_array($role, ['user', 'admin'])) {
            throw new Exception("Invalid user role specified.");
        }

        // Hash the user's password for secure storage
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        // Insert user details into the database
        $stmt = $this->connection->prepare(
            "INSERT INTO users (name, phone, email, password, type) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssss', $fullName, $mobile, $emailAddress, $hashedPassword, $role);
        $stmt->execute();
        $stmt->close();

        return $this->connection->insert_id();
    }

    // Logs in a user by verifying credentials; returns user info on success
    public function loginUser($emailAddress, $enteredPassword) {
        $stmt = $this->connection->prepare(
            "SELECT id, name, password, type 
             FROM users WHERE email = ?"
        );
        $stmt->bind_param('s', $emailAddress);
        $stmt->execute();
        $stmt->bind_result($userId, $fullName, $storedHash, $role);
        if ($stmt->fetch() && password_verify($enteredPassword, $storedHash)) {
            $stmt->close();
            return ['id' => $userId, 'name' => $fullName, 'type' => $role];
        }
        $stmt->close();
        return false;
    }

    // Retrieves all users; can filter by currently checked-in status
    public function getAllUsers($onlyActiveSessions = false) {
        if ($onlyActiveSessions) {
            $sql = "SELECT u.* 
                    FROM users u
                    JOIN sessions s ON u.id = s.user_id
                    WHERE s.check_out IS NULL
                    GROUP BY u.id";
            return $this->connection->query($sql);
        }
        return $this->connection->query("SELECT * FROM users");
    }
}
?>
