<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$userService = new User($database);
$showOnlyCheckedIn = isset($_GET['checkedin']);
$userList = $userService->getAllUsers($showOnlyCheckedIn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Users – EasyEV</title>
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

    .content-wrapper {
      max-width: 1100px;
      min-height: 100vh;
      margin: 0 auto;
      padding: 40px 20px;
    }

    h2 {
      text-align: center;
      color: #185519;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      margin-top: 25px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: center;
      border-bottom: 1px solid #ddd;
      font-size: 14px;
    }

    th {
      background-color: #185519;
      color: #ffffff;
    }

    tr:hover {
      background-color: #FCDE70;
      transition: 0.3s;
    }

    .back-link {
      text-align: center;
      margin-top: 30px;
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

  <?php include("header.php"); ?>

  <div class="content-wrapper">
    <h2><?= $showOnlyCheckedIn ? 'Active chargings' : 'Registered users' ?></h2>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Type</th>
      </tr>
      <?php while ($user = $userList->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($user['id']) ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['phone']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= htmlspecialchars($user['type']) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="back-link">
      <p><a href="index.php">Back to Dashboard</a></p>
    </div>
  </div>

  <?php include("footer.php"); ?>

</body>
</html>
