<?php
$conn = new mysqli("127.0.0.1", "root", "", "finsight");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password =
    password_hash($_POST['password'],
    PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration Successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Finsight</title>
    <link rel="stylesheet" href="app_theme.css">
</head>
<body>

<div class="topbar">
  <div class="brand"><span class="dot"></span>Finsight</div>
  <div class="menu-grid">
    <a class="btn btn-outline" href="landing.php">Home</a>
    <a class="btn" href="login.php">Login</a>
  </div>
</div>

<div class="form-wrap">
  <h2 style="text-align:center;">Create your account ✨</h2>

  <?php if (isset($_GET['registered'])) { ?>
    <p class="small-note" style="text-align:center; color: #86efac;">Registration successful! Please login.</p>
  <?php } ?>

  <form method="POST" action="">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <div style="margin-top:18px; display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
      <button class="btn btn-primary" type="submit" name="register">Register</button>
    </div>

  </form>

</div>

</body>
</html>
