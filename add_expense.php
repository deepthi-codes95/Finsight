<?php
session_start();
$conn = new mysqli("127.0.0.1","root","","finsight");

if(isset($_POST['submit'])){
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    // Force date to CURRENT date (ignore selected date)
    $date = date('Y-m-d');
    $description = $_POST['description'];

    // DEBUG
    // echo "<div style='color:blue'>ADD DEBUG date={$date}</div>";

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO expenses (user_id, amount, category, date, description)
            VALUES ('$user_id','$amount','$category','$date','$description')";

    if($conn->query($sql) === TRUE){
        $success = "Expense Added Successfully!";
    }else{
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Expense - Finsight</title>
  <link rel="stylesheet" href="app_theme.css">
</head>

<body>

  <div class="topbar">
    <div class="brand"><span class="dot"></span>Finsight</div>
    <div class="menu-grid">
      <a class="btn btn-outline" href="dashboard.php">⬅ Dashboard</a>
      <a class="btn btn-outline" href="view_expense.php">📄 View</a>
      <a class="btn" href="logout.php">🚪 Logout</a>
    </div>
  </div>

  <div class="form-wrap">
    <h2 style="text-align:center;">➕ Add New Expense</h2>

    <?php if(isset($success)){ echo "<div class='small-note' style='color:#34d399; font-weight:800; margin-top:12px;'>".$success."</div>"; } ?>
    <?php if(isset($error)){ echo "<div class='small-note' style='color:#fca5a5; font-weight:800; margin-top:12px;'>".$error."</div>"; } ?>

    <form method="POST" action="add_expense.php" style="margin-top:18px;">

      <label>Amount</label>
      <input type="number" name="amount" required placeholder="e.g. 2500">

      <label>Category</label>
      <select name="category" required>
        <option value="Food">Food</option>
        <option value="Travel">Travel</option>
        <option value="Shopping">Shopping</option>
        <option value="Bills">Bills</option>
        <option value="Dress">Dress</option>
        <option value="Other">Other</option>
      </select>

      <label>Date</label>
      <input type="date" name="date" required>

      <label>Description (optional)</label>
      <input type="text" name="description" placeholder="Short note">

      <div style="margin-top:18px; display:flex; justify-content:center;">
        <button class="btn btn-primary" type="submit" name="submit">Add Expense</button>
      </div>

    </form>

    <div class="small-note" style="text-align:center; margin-top:14px;">
      <a href="dashboard.php" style="text-decoration:none; color:var(--brand); font-weight:800;">Back to Dashboard</a>
    </div>
  </div>

</body>
</html>

