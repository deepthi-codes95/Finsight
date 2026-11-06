e k<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? "User";

/* TOTAL EXPENSE */
$sql = "SELECT SUM(amount) AS total FROM expenses WHERE user_id=$user_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total = $row['total'] ?? 0;

/* BUDGET */
$sql2 = "SELECT amount FROM budget WHERE user_id=$user_id AND category='Monthly' ORDER BY id DESC LIMIT 1";
$res2 = $conn->query($sql2);
$row2 = $res2->fetch_assoc();

$budget = $row2['amount'] ?? 0;

/* REMAINING */
$remaining = $budget - $total;

$budget_percent = null;
if ($budget > 0) {
    $budget_percent = ($total / $budget) * 100;
}

/* EXPENSE LIST */
$sql3 = "SELECT * FROM expenses WHERE user_id=$user_id";
$result3 = $conn->query($sql3);

/* CHART DATA */
$categories = [];
$amounts = [];

$sql4 = "SELECT category, SUM(amount) as total FROM expenses WHERE user_id=$user_id GROUP BY category";
$result4 = $conn->query($sql4);

while($row4 = $result4->fetch_assoc()){
    $categories[] = $row4['category'];
    $amounts[] = $row4['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Finsight Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<link rel="stylesheet" href="app_theme.css">
</head>

<body>

<div class="topbar">
  <div class="brand"><span class="dot"></span>Finsight</div>
  <div class="menu-grid">
    <a class="btn btn-outline" href="add_expense.php">➕ Add</a>
    <a class="btn btn-outline" href="view_expense.php">📄 View</a>
    <a class="btn btn-outline" href="set_budget.php">💰 Budget</a>
    <a class="btn btn-outline" href="report_preview.php">🧾 PDF</a>
    <a class="btn" href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="container">

  <?php
  // Budget alerts
  if ($budget > 0 && $remaining < 0) {
      echo "<div class='card' style='padding:14px 18px; margin-bottom:16px; border-color: rgba(239,68,68,.55); background: rgba(239,68,68,.10);'>";
      echo "<div style='text-align:center; font-weight:900; color:#fecaca;'>⚠ Budget exceeded! You used ₹" . htmlspecialchars((string)$total) . " of ₹" . htmlspecialchars((string)$budget) . "</div>";
      echo "</div>";
  } elseif ($budget > 0 && $budget_percent !== null && $budget_percent >= 80) {
      echo "<div class='card' style='padding:14px 18px; margin-bottom:16px; border-color: rgba(245,158,11,.55); background: rgba(245,158,11,.10);'>";
      echo "<div style='text-align:center; font-weight:900; color:#fde68a;'>⏳ Approaching budget limit! Used " . round($budget_percent, 1) . "%</div>";
      echo "</div>";
  }
  ?>

  <div class="card" style="padding:18px; margin-bottom:16px;">
    <h2 style="text-align:center; margin-bottom:8px;">💰 Expense Dashboard</h2>
    <div style="text-align:center; color: var(--muted); font-weight:700;">Welcome, <?php echo htmlspecialchars($user_name); ?> 👋</div>
  </div>

  <div class="grid-3">
    <div class="card kpi">
      <div class="label">Total Expense</div>
      <div class="value">₹ <?php echo htmlspecialchars($total); ?></div>
    </div>
    <div class="card kpi">
      <div class="label">Monthly Budget</div>
      <div class="value">₹ <?php echo htmlspecialchars($budget); ?></div>
    </div>
    <div class="card kpi">
      <div class="label">Remaining</div>
      <div class="value" style="color: <?php echo ($remaining < 0) ? '#fca5a5' : '#34d399'; ?>;">
        ₹ <?php echo htmlspecialchars($remaining); ?>
      </div>
    </div>
  </div>

  <div class="card" style="padding:18px; margin-top:16px;">
    <h3 style="margin-bottom:10px;">Category Breakdown</h3>
    <div style="width:min(520px, 100%); margin:0 auto;">
      <canvas id="myChart"></canvas>
      <?php if(count($categories) === 0){ echo "<p class='small-note' style='text-align:center;margin-top:12px;'>No expenses yet. Add an expense to see the pie chart.</p>"; } ?>
    </div>
  </div>

  <div class="card" style="padding:18px; margin-top:16px;">
    <h3 style="margin-bottom:12px;">Expense Details</h3>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>

<?php
if($result3 && $result3->num_rows > 0){
    while($row = $result3->fetch_assoc()){
        $id = (int)$row['id'];
        echo "<tr>
            <td>".$row['date']."</td>
            <td>".$row['description']."</td>
            <td>".$row['category']."</td>
            <td>₹".$row['amount']."</td>
            <td><a href='edit_expense.php?id=$id'>Edit</a></td>
            <td><a href='delete_expense.php?id=$id' onclick=\"return confirm('Delete this expense?')\">Delete</a></td>
        </tr>";
    }
}
?>

        </tbody>
      </table>
    </div>
  </div>

  <div class="footer">© 2024 Finsight. Expense tracking made simple.</div>

<script>
const ctx = document.getElementById('myChart');

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: <?php echo json_encode($categories); ?>,
    datasets: [{
      data: <?php echo json_encode($amounts); ?>,
      backgroundColor: [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      datalabels: {
        color: '#ffffff',
        font: { weight: 'bold', size: 12 },
        anchor: 'center',
        align: 'center',
        formatter: function(value, context) {
          const dataset = context.chart.data.datasets[0];
          const total = dataset.data.reduce((a, b) => a + b, 0);
          if (!total) return '0%';
          const pct = (value / total) * 100;
          return pct.toFixed(1) + '%';
        }
      }
    }
  }
});
</script>

</body>
</html>

