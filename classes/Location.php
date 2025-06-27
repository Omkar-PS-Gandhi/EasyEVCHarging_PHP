<?php

class Location {
    private $connection;

    public function __construct(Database $database) {
        $this->connection = $database;
    }

    // Add a new location or update an existing one
    public function newChargingLocation($locationId, $locationDesc, $totalPorts, $hourlyRate) {
        if ($locationId) {
            // Update existing location
            $stmt = $this->connection->prepare(
              "UPDATE locations 
               SET description=?, total_stations=?, cost_per_hour=? 
               WHERE location_id=?"
            );
            $stmt->bind_param('sidi', $locationDesc, $totalPorts, $hourlyRate, $locationId);
        } else {
            // Insert a new location
            $stmt = $this->connection->prepare(
              "INSERT INTO locations (description, total_stations, cost_per_hour)
               VALUES (?, ?, ?)"
            );
            $stmt->bind_param('sid', $locationDesc, $totalPorts, $hourlyRate);
        }
        $stmt->execute();
        $stmt->close();
        return $locationId ?: $this->connection->insert_id();
    }

    // Search for locations by description or ID
    public function search($searchTerm = '') {
        $query = "SELECT * FROM locations 
                  WHERE description LIKE ? OR CAST(location_id AS CHAR) LIKE ?";
        $likeTerm = "%{$searchTerm}%";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('ss', $likeTerm, $likeTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Count active (ongoing) charging sessions at a location
    private function countActiveSessions($locationId) {
        $stmt = $this->connection->prepare(
          "SELECT COUNT(*) FROM sessions 
           WHERE location_id=? AND check_out IS NULL"
        );
        $stmt->bind_param('i', $locationId);
        $stmt->execute();
        $stmt->bind_result($activeCount);
        $stmt->fetch();
        $stmt->close();
        return $activeCount;
    }

    // List all locations with available charging ports calculated
    public function listAllWithAvailability() {
        $data = $this->connection->query("SELECT * FROM locations");
        $locations = [];
        while ($location = $data->fetch_assoc()) {
            $occupied = $this->countActiveSessions($location['location_id']);
            $location['available'] = $location['total_stations'] - $occupied;
            $locations[] = $location;
        }
        return $locations;
    }
}
?>
