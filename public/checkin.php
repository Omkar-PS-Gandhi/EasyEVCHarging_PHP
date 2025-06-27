<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'user') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$locationService = new Location($database);
$sessionService = new Session($database, $locationService);

$formErrors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedLocationId = (int)$_POST['location_id'];
    try {
        $newSessionId = $sessionService->chargerPlugIn($_SESSION['user']['id'], $selectedLocationId);
        $successMessage = "Checked in successfully! Session ID: {$newSessionId}";
    } catch (Exception $e) {
        $formErrors[] = $e->getMessage();
    }
}

$availableLocations = $locationService->listAllWithAvailability();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Check-In – EasyEV</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #F5F7F8;
      padding: 0;
      margin: 0;
    }

    .main-content {
      padding: 40px 70px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 70vh;
    }

    .form-container {
      background: #ffffff;
      max-width: 500px;
      width: 100%;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      border-left: 5px solid #E8B86D;
    }

    h2 {
      text-align: center;
      color: #185519;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 10px;
      color: #185519;
    }

    select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-bottom: 20px;
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

    .feedback-success {
      color: #185519;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .feedback-error {
      color: red;
      margin-bottom: 15px;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
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

  <div class="main-content">
    <div class="form-container">
      <h2>Check-In for Charging</h2>

      <?php if ($formErrors): ?>
        <div class="feedback-error">
          <ul>
            <?php foreach ($formErrors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($successMessage): ?>
        <div class="feedback-success">
          <p><?= htmlspecialchars($successMessage) ?></p>
        </div>
      <?php endif; ?>

      <form method="post">
        <label for="location_id">Choose Location</label>
        <select name="location_id" id="location_id" required>
          <?php foreach ($availableLocations as $location): ?>
            <?php if ($location['available'] > 0): ?>
              <option value="<?= $location['location_id'] ?>">
                (<?= $location['location_id'] ?>)
                <?= htmlspecialchars($location['description']) ?>
                — Available: <?= $location['available'] ?>
              </option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <button type="submit">Check-In</button>
      </form>

      <div class="back-link">
        <p><a href="index.php">Back to Dashboard</a></p>
      </div>
    </div>
  </div>

  <?php include("footer.php") ?>
</body>
</html>
