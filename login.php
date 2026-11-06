<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        if(password_verify($password, $row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            header("Location: dashboard.php");
        } else {
            echo "Invalid Password!";
        }
    } else {
        echo "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Finsight</title>
    <link rel="stylesheet" href="app_theme.css">
</head>
<body>

<div class="topbar">
  <div class="brand"><span class="dot"></span>Finsight</div>
  <div class="menu-grid">
    <a class="btn btn-outline" href="landing.php">Home</a>
    <a class="btn" href="register.php">Register</a>
  </div>
</div>

<div class="form-wrap">
  <h2 style="text-align:center; margin-top:6px;">Welcome Back 👋</h2>
  <p class="small-note" style="text-align:center; margin-bottom:18px;">Login to view your dashboard and download reports.</p>

  <form method="POST">
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>