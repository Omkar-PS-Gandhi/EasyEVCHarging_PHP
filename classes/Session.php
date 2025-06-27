<?php

class Session {
    private $connection;
    private $locationModel;

    public function __construct(Database $database, Location $locationModel) {
        $this->connection = $database;
        $this->locationModel = $locationModel;
    }

    // Starts a new charging session (check-in)
    public function chargerPlugIn($userId, $chargingLocationId) {
        //Check for available slots at the location
        $locationData = $this->locationModel->listAllWithAvailability();
        foreach ($locationData as $location) {
            if ($location['location_id'] == $chargingLocationId && $location['available'] > 0) {
                $stmt = $this->connection->prepare(
                  "INSERT INTO sessions (user_id, location_id, check_in) 
                   VALUES (?, ?, NOW())"
                );
                $stmt->bind_param('ii', $userId, $chargingLocationId);
                $stmt->execute();
                $stmt->close();
                return $this->connection->insert_id();
            }
        }
        throw new Exception("No station available at the selected location.");
    }

    // Ends a session (check-out) and calculates the cost
    public function chargerUnplug($sessionId) {
        //Get session start time and location
        $stmt = $this->connection->prepare(
          "SELECT location_id, check_in FROM sessions WHERE session_id=?"
        );
        $stmt->bind_param('i', $sessionId);
        $stmt->execute();
        $stmt->bind_result($locationId, $checkInTime);
        $stmt->fetch();
        $stmt->close();

        //Calculate charging duration in hours
        if (!$checkInTime) {
             throw new Exception("Invalid check-in time.");
        }   

        $startTime = new DateTime($checkInTime);
        $endTime = new DateTime(); 

        $interval = $startTime->diff($endTime);
        $durationHours = $interval->days * 24 + $interval->h + ($interval->i / 60) + ($interval->s / 3600);
        $durationHours = round($durationHours, 2);


        //Retrieve hourly rate for the location
        $rateStmt = $this->connection->prepare(
          "SELECT cost_per_hour FROM locations WHERE location_id=?"
        );
        $rateStmt->bind_param('i', $locationId);
        $rateStmt->execute();
        $rateStmt->bind_result($hourlyRate);
        $rateStmt->fetch();
        $rateStmt->close();

        $totalCost = round($durationHours * $hourlyRate, 2);

        //Update session record with check-out time and cost
        $updateStmt = $this->connection->prepare(
          "UPDATE sessions SET check_out=NOW(), cost=? 
           WHERE session_id=?"
        );
        $updateStmt->bind_param('di', $totalCost, $sessionId);
        $updateStmt->execute();
        $updateStmt->close();

        return $totalCost;
    }

    // Retrieves a user's active sessions
    public function userActiveSession($userId, $onlyOngoing = false) {
        $query = "SELECT * FROM sessions WHERE user_id=? ";
        $query .= $onlyOngoing ? "AND check_out IS NULL" : "AND check_out IS NOT NULL";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
