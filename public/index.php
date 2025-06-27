<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - EasyEV</title>
  <style>

    *{
      box-sizing: border-box;
    }
   body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #F5F7F8;
  color: #185519;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  min-height: 130vh;
}

.main {
  width: 100vw;
  height: fit-content;
  padding: 70px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.dashboard-wrapper {
  background: #ffffff;
  padding: 40px 70px;
  border-radius: 15px;
  box-shadow: 0 8px 24px rgba(232, 184, 109, 0.2);
  width: 100%;
  max-width: 1000px;
}

.nav-links {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 25px;
}

.nav-item {
  display: flex;
  background: #ffffff;
  border: 2px solid #FCDE70;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
}

.nav-item:hover {
  transform: scale(1.02);
  box-shadow: 0 6px 14px rgba(232, 184, 109, 0.3);
  border-color: #E8B86D;
}

.nav-img {
  width: 40%;
  background-size: cover;
  background-position: center;
  min-height: 100px;
  background-color: #FCDE70;
}

.nav-content {
  width: 60%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-color: #F5F7F8;
}

.nav-content a {
  font-size: 16px;
  color: #185519;
  font-weight: bold;
  text-decoration: none;
  text-align: center;
}

.nav-content a:hover {
  color: #E8B86D;
}


  </style>
</head>
<body>

  <?php include("header.php"); ?>
<div class="main">
  <div class="dashboard-wrapper"> 

    <!-- <h3>Navigation</h3> -->
    <div class="nav-links">
      <?php if ($currentUser['type'] === 'admin'): ?>
        
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/location-add.jpg');"></div>
          <div class="nav-content"><a href="insert_location.php">Manage Charging Locations</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/list-locations.jpg');"></div>
          <div class="nav-content"><a href="locations.php">Complete Location Directory</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/station-available.jpg');"></div>
          <div class="nav-content"><a href="locations.php?filter=available">Available Chargers Nearby</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/station-full.jpg');"></div>
          <div class="nav-content"><a href="locations.php?filter=full">Fully Occupied Stations</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/users.jpg');"></div>
          <div class="nav-content"><a href="users.php">Registered User List</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/charging.jpg');"></div>
          <div class="nav-content"><a href="users.php?checkedin=1">Active Charging Sessions</a></div>
        </div>
      <?php else: ?>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/station-available.jpg');"></div>
          <div class="nav-content"><a href="locations.php?filter=available">Stations with Availability</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/session.jpg');"></div>
          <div class="nav-content"><a href="sessions.php?active=1">Ongoing Charging Sessions</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/checkin.jpg');"></div>
          <div class="nav-content"><a href="checkin.php">Plug In & Begin</a></div>
        </div>
        <div class="nav-item">
          <div class="nav-img" style="background-image: url('../images/checkout.jpg');"></div>
          <div class="nav-content"><a href="checkout.php">Unplug & Complete</a></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include("footer.php") ?>
</body>
</html>
