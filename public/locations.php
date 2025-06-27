<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$locationService = new Location($database);

$filterType = $_GET['filter'] ?? '';
$searchQuery = trim($_GET['search'] ?? '');
$filteredLocations = [];

foreach ($locationService->listAllWithAvailability() as $location) {
    if ($filterType === 'available' && $location['available'] <= 0) continue;
    if ($filterType === 'full' && $location['available'] > 0) continue;
    if (
        $searchQuery !== '' &&
        stripos($location['description'], $searchQuery) === false &&
        stripos((string)$location['location_id'], $searchQuery) === false
    ) continue;

    $filteredLocations[] = $location;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Locations – EasyEV</title>
  <style>
    *{
      box-sizing: border-box ;
    }
    body {
  font-family: Arial, sans-serif;
  background-color: #F5F7F8;
  padding: 0;
  margin: 0;
}

.main{
  width: 100vw;
  height: fit-content;
  padding: 40px 70px;
}

h2 {
  text-align: center;
  color: #185519;
}

form {
  text-align: center;
  margin-bottom: 25px;
}

input[type="text"] {
  padding: 10px;
  width: 250px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

button {
  padding: 10px 15px;
  background-color: #185519;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

button:hover {
  background-color: #E8B86D;
  color: #185519;
}

.card-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-top: 30px;
}

.location-card {
  display: flex;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: transform 0.3s;
}

.location-card:hover {
  transform: scale(1.02);
}

.card-img-wrapper {
  flex: 0 0 150px;
  background-color: #FCDE70;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-details {
  flex: 1;
  padding: 20px;
  background-color: #F5F7F8;
}

.card-details h3 {
  margin: 0 0 10px;
  font-size: 18px;
  color: #185519;
}

.card-details p {
  margin: 5px 0;
  font-size: 14px;
  color: #333;
}

.edit-link {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #185519;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
}

.edit-link:hover {
  background-color: #E8B86D;
  color: #185519;
}

.back-link {
  text-align: center;
  margin-top: 40px;
}

.back-link a {
  padding: 10px 20px;
  background-color: #185519;
  color: white;
  border-radius: 10px;
  text-decoration: none;
  font-weight: bold;
}

.back-link a:hover {
  background-color: #E8B86D;
  color: #185519;
  transition: 0.4s;
}

  </style>
</head>

<body>
  <?php include("header.php") ?>
  <div class="main">
  <h2>Charging Locations</h2>

  <form method="get">
    <label>
      <input type="text" name="search" placeholder="Search by ID or description" value="<?= htmlspecialchars($searchQuery) ?>">
    </label>
    <button type="submit">Search</button>
  </form>

  <div class="card-grid">
    <?php foreach ($filteredLocations as $entry): ?>
      <div class="location-card">
        <div class="card-img-wrapper">
          <img src="../images/charger.jpg" alt="Charger Image">
        </div>
        <div class="card-details">
          <h3><?= htmlspecialchars($entry['description']) ?></h3>
          <p><strong>ID:</strong> <?= htmlspecialchars($entry['location_id']) ?></p>
          <p><strong>Total Stations:</strong> <?= htmlspecialchars($entry['total_stations']) ?></p>
          <p><strong>Available:</strong> <?= htmlspecialchars($entry['available']) ?></p>
          <p><strong>Cost per Hour:</strong> $<?= htmlspecialchars($entry['cost_per_hour']) ?></p>
          <?php if ($_SESSION['user']['type'] === 'admin'): ?>
            <a class="edit-link" href="insert_location.php?id=<?= $entry['location_id'] ?>">Edit</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="back-link">
    <p><a href="index.php">Back to Dashboard</a></p>
  </div>
  </div>

  <?php include("footer.php") ?>
</body>
</html>
