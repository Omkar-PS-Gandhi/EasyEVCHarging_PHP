<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$locationManager = new Location($database);
$sessionManager = new Session($database, $locationManager);
$currentUserId = $_SESSION['user']['id'];
$showActiveOnly = isset($_GET['active']);
$sessionList = $sessionManager->userActiveSession($currentUserId, $showActiveOnly);
?>
<!DOCTYPE html>
<html>
<head>
  <title>My <?= $showActiveOnly ? 'Active' : 'Past' ?> Active sessions</title>
  <style>
    *{
      box-sizing: border-box;
    }
body {
  font-family: Arial, sans-serif;
  background: #F5F7F8;
  padding: 0;
  margin: 0;
}

h2 {
  text-align: center;
  color: #185519;
}

.card-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin: 30px auto 0;
  max-width: 1100px;
  padding: 0 20px;
}

.session-card {
  display: flex;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: transform 0.3s;
  border-left: 5px solid #E8B86D;
}

.session-card:hover {
  transform: scale(1.02);
}

.card-img-wrapper {
  flex: 0 0 120px;
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
  transition: 0.3s;
}

   

  </style>
</head>
<body>
  <?php include("header.php") ?>
  <h2>My <?= $showActiveOnly ? 'Current active' : '' ?> Sessions</h2>

  <div class="card-grid">
    <?php while ($session = $sessionList->fetch_assoc()): ?>
      <div class="session-card">
        <div class="card-img-wrapper">
          <img src="../images/charger.jpg" alt="Session Image">
        </div>
        <div class="card-details">
          <h3>Session #<?= htmlspecialchars($session['session_id']) ?></h3>
          <p><strong>Location ID:</strong> <?= htmlspecialchars($session['location_id']) ?></p>
          <p><strong>Check In:</strong> <?= htmlspecialchars($session['check_in']) ?></p>
          <p><strong>Check Out:</strong> <?= htmlspecialchars($session['check_out'] ?? '—') ?></p>
          <p><strong>Cost:</strong> $<?= htmlspecialchars($session['cost'] ?? '—') ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <div class="back-link">
    <p><a href="index.php">Back to Dashboard</a></p>
  </div>

  <?php include("footer.php") ?>

</body>
</html>
