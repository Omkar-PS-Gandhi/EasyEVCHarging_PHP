<?php
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$currentUser = $_SESSION['user'];
?>

<style>
  .main-header {
    width: 100vw;
    height: 6em;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #ffffff;
    padding: 0 40px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    border-bottom: 1px solid #e0e0e0;
  }

  .main-header .logo {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 20px;
    font-weight: bold;
    color: #1b4965;
    text-decoration: none;
  }

  .main-header .logo img {
    height: 48px;
  }

  .main-header .user-section {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .main-header .username {
    font-size: 14px;
    font-weight: 500;
    color: #1d3557;
    background-color: #f1f5f8;
    padding: 8px 12px;
    border-radius: 8px;
  }

  .main-header .logout-btn {
    text-decoration: none;
    font-size: 14px;
    background-color: #e63946;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    font-weight: bold;
    transition: background 0.3s;
  }

  .main-header .logout-btn:hover {
    background-color: #c92a37;
  }
</style>

<div class="main-header">
  <a href="index.php" class="logo">
    <img src="../images/logo.png" alt="EasyEV Logo">
    EasyEV <br> Charging
  </a>
  <div class="user-section">
    <div class="username">
      <?= htmlspecialchars($currentUser['name']) ?> (<?= htmlspecialchars($currentUser['type']) ?>)
    </div>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</div>
