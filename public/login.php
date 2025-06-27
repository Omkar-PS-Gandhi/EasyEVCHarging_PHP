<?php
require_once __DIR__ . '/../config.php';

$databaseInstance = new Database();
$accountManager = new User($databaseInstance);

$validationErrors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputEmail = trim($_POST['email'] ?? '');
    $inputPassword = $_POST['password'] ?? '';

    if ($inputEmail === '') $validationErrors[] = 'Email is required.';
    if ($inputPassword === '') $validationErrors[] = 'Password is required.';

    if (empty($validationErrors)) {
        $authenticatedUser = $accountManager->loginUser($inputEmail, $inputPassword);
        if ($authenticatedUser) {
            $_SESSION['user'] = $authenticatedUser;
            header('Location: index.php');
            exit;
        } else {
            $validationErrors[] = 'Invalid credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sign in</title>
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

.login-container {
  background: #fff;
  padding: 30px 35px;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 400px;
  border-top: 6px solid #E8B86D;
}

h2 {
  margin-bottom: 20px;
  text-align: center;
  color: #185519;
}

label {
  display: block;
  margin: 10px 0 5px;
  font-weight: bold;
  color: #185519;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background-color: #fdfdfd;
}

button {
  width: 100%;
  padding: 12px;
  background: #185519;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  font-weight: bold;
}

button:hover {
  background: #E8B86D;
  color: #185519;
}

.error-messages {
  color: red;
  margin-bottom: 15px;
  font-size: 14px;
}

.register-link {
  text-align: center;
  margin-top: 15px;
  font-size: 14px;
}

.register-link a {
  color: #007BFF;
  text-decoration: none;
  font-weight: bold;
}

.register-link a:hover {
  text-decoration: underline;
}

  </style>
</head>
<body>
<div class="login-container">
  <h2>Login</h2>
  <?php if ($validationErrors): ?>
    <div class="error-messages">
      <ul>
        <?php foreach ($validationErrors as $message): ?>
          <li><?= htmlspecialchars($message) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <form method="post" action="">
    <label>Email:</label>
    <input type="text" name="email" value="<?= htmlspecialchars($inputEmail ?? '') ?>">
    
    <label>Password:</label>
    <input type="password" name="password">

    <button type="submit">Login</button>
  </form>
  <div class="register-link">
    <p> Wanna, Join for a green change? <a href="register.php">Register here</a>.</p>
  </div>
</div>
</body>
</html>
