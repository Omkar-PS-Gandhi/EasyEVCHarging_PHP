<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$locationService = new Location($database);
$formErrors = [];

$locationId = $_GET['id'] ?? null;
$locationDescription = '';
$totalStations = '';
$hourlyRate = '';

if ($locationId) {
    $stmt = $database->prepare(
        "SELECT description, total_stations, cost_per_hour 
         FROM locations 
         WHERE location_id = ?"
    );
    $stmt->bind_param('i', $locationId);
    $stmt->execute();
    $stmt->bind_result($locationDescription, $totalStations, $hourlyRate);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locationDescription = trim($_POST['description'] ?? '');
    $totalStations = (int)($_POST['total_stations'] ?? 0);
    $hourlyRate = (float)($_POST['cost_per_hour'] ?? 0);

    if ($locationDescription === '') $formErrors[] = 'Description required.';
    if ($totalStations <= 0) $formErrors[] = 'Stations must be > 0.';
    if ($hourlyRate <= 0) $formErrors[] = 'Cost must be > 0.';

    if (empty($formErrors)) {
        $locationService->newChargingLocation($locationId, $locationDescription, $totalStations, $hourlyRate);
        header('Location: locations.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= $locationId ? 'Edit' : 'Add' ?> Location – EasyEV</title>
  <style>
    *{
      box-sizing: border-box;
    }
    body {
  font-family: Arial, sans-serif;
  background: #F5F7F8;
  margin: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  min-height: 100vh;
}

.form-container {
  background: #fff;
  padding: 35px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
  width: 100%;
  max-width: 500px;
  border-top: 5px solid #E8B86D;
}

h2 {
  text-align: center;
  color: #185519;
  margin-bottom: 25px;
}

label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
  color: #185519;
}

input {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
  background-color: #fcfcfc;
}

button {
  width: 100%;
  padding: 12px;
  background-color: #185519;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background-color: #E8B86D;
  color: #185519;
}

.error-list {
  color: red;
  margin-bottom: 20px;
  font-size: 14px;
}

.back-link {
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
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
  transition: 0.3s;
}

  </style>
</head>
<body>
  <?php include("header.php") ?>
  <div class="form-container">
    <h2><?= $locationId ? 'Edit' : 'Add' ?> Location</h2>

    <?php if ($formErrors): ?>
      <ul class="error-list">
        <?php foreach ($formErrors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="post">
      <label>Station name</label>
      <input type="text" name="description" value="<?= htmlspecialchars($locationDescription) ?>">

      <label>Total Stations</label>
      <input type="number" name="total_stations" value="<?= htmlspecialchars($totalStations) ?>">

      <label>Cost per Hour</label>
      <input type="text" name="cost_per_hour" value="<?= htmlspecialchars($hourlyRate) ?>">

      <button type="submit"><?= $locationId ? 'Update' : 'Add' ?> Location</button>
    </form>

    <div class="back-link">
      <p><a href="index.php">Back to Dashboard</a></p>
    </div>
  </div>
<?php include("footer.php") ?>
</body>
</html>
