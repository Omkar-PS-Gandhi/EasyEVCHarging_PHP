<?php
require_once __DIR__ . '/../config.php';

$databaseConnection = new Database();
$accountHandler = new User($databaseConnection);

$formErrors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['name'] ?? '');
    $mobileNumber = trim($_POST['phone'] ?? '');
    $emailAddress = trim($_POST['email'] ?? '');
    $userPassword = $_POST['password'] ?? '';
    $accountRole = $_POST['type'] ?? 'user';

    if ($fullName === '') $formErrors[] = 'Name is required.';
    if ($emailAddress === '' || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) $formErrors[] = 'Valid email is required.';
    if ($userPassword === '' || strlen($userPassword) < 6) $formErrors[] = 'Password must be at least 6 characters.';
    if (!in_array($accountRole, ['user','admin'])) $formErrors[] = 'Invalid user type.';

    if (empty($formErrors)) {
        try {
            $newUserId = $accountHandler->registerUser($fullName, $mobileNumber, $emailAddress, $userPassword, $accountRole);
            $_SESSION['user'] = ['id' => $newUserId, 'name' => $fullName, 'type' => $accountRole];
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $formErrors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - EasyEV</title>
  <style>
   body {
  font-family: Arial, sans-serif;
  background: #F5F7F8;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.register-container {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 0 12px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 450px;
  border-top: 5px solid #E8B86D;
}

h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #185519;
}

label {
  display: block;
  margin: 12px 0 6px;
  font-weight: bold;
  color: #185519;
}

input, select {
  width: 100%;
  padding: 10px;
  margin-bottom: 14px;
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
  cursor: pointer;
  font-weight: bold;
}

button:hover {
  background-color: #E8B86D;
  color: #185519;
}

.error-box {
  color: red;
  margin-bottom: 16px;
  font-size: 14px;
}

.login-link {
  text-align: center;
  margin-top: 12px;
  font-size: 14px;
}

.login-link a {
  color: #007BFF;
  text-decoration: none;
  font-weight: bold;
}

.login-link a:hover {
  text-decoration: underline;
}

  </style>
</head>
<body>
<div class="register-container">
  <h2>Create an Account</h2>
  <?php if ($formErrors): ?>
    <div class="error-box">
      <ul>
        <?php foreach ($formErrors as $errorMsg): ?>
          <li><?= htmlspecialchars($errorMsg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form method="post" action="">
    <label>Full Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($fullName ?? '') ?>">

    <label>Phone Number</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($mobileNumber ?? '') ?>">

    <label>Email Address</label>
    <input type="text" name="email" value="<?= htmlspecialchars($emailAddress ?? '') ?>">

    <label>Password</label>
    <input type="password" name="password">

    <label>Account Type</label>
    <select name="type">
      <option value="user" <?= (isset($accountRole) && $accountRole === 'user') ? 'selected' : '' ?>>User</option>
      <option value="admin" <?= (isset($accountRole) && $accountRole === 'admin') ? 'selected' : '' ?>>Admin</option>
    </select>

    <button type="submit">Register</button>
  </form>
  <div class="login-link">
    <p>Already a part of green change? <a href="login.php">Login here</a>.</p>
  </div>
</div>
</body>
</html>
