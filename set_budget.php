<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Use same DB as the rest of the project
$conn = new mysqli("127.0.0.1","root","","finsight");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$error = "";

if(isset($_POST['submit'])){
    $budget = $_POST['budget'];
    $month = $_POST['month'];

    // Fallback: your DB columns are `budget` (amount), `expenses`, `user`.
    // We must insert into exactly those columns.


    // Budget table structure (from phpMyAdmin) contains only: id, user_id, category, amount
    // So for budget we will store it in: category = 'Monthly' and amount = budget.
    // If you want to store real months, you would need a `month` column in DB.
    $category = 'Monthly';
    $sql = "INSERT INTO budget (user_id, category, amount) VALUES ('$user_id','$category','$budget')";







    if($conn->query($sql) === TRUE){
        $message = "Budget Saved Successfully";
    }else{
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Budget - Finsight</title>
    <link rel="stylesheet" href="app_theme.css">
</head>
<body>

<div class="topbar">
  <div class="brand"><span class="dot"></span>Finsight</div>
  <div class="menu-grid">
    <a class="btn btn-outline" href="dashboard.php">⬅ Dashboard</a>
    <a class="btn btn-outline" href="add_expense.php">➕ Add</a>
    <a class="btn" href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="form-wrap">

<h2 style="text-align:center; margin-top:0;">💰 Set Monthly Budget</h2>


<?php if($message){ echo "<p style='color:green;'>$message</p>"; } ?>
<?php if($error){ echo "<p style='color:red;'>$error</p>"; } ?>

<form method="POST" action="set_budget.php">
    Budget Amount:
    <input type="number" name="budget" required>
    <br><br>

    Month:
    <input type="month" name="month" required>
    <br><br>

    <input type="submit" name="submit" value="Set Budget">

</form>

<br>
<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>

