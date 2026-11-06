<?php
$conn = new mysqli("127.0.0.1","root","","finsight");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

/* TOTAL EXPENSE */
$sql_total = "SELECT SUM(amount) AS total FROM expenses";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_expense = $row_total['total'] ?? 0;

/* BUDGET */
$sql_budget = "SELECT * FROM budget LIMIT 1";
$result_budget = $conn->query($sql_budget);
$row_budget = $result_budget->fetch_assoc();
$budget_amount = $row_budget['amount'] ?? 0;

/* REMAINING */
$remaining_budget = $budget_amount - $total_expense;

/* EXPENSES */
$sql_expense = "SELECT * FROM expenses ORDER BY date DESC";
$result_expense = $conn->query($sql_expense);

/* CATEGORY DATA */
$chart_data = [];
$sql_chart = "SELECT category, SUM(amount) AS total FROM expenses GROUP BY category";
$result_chart = $conn->query($sql_chart);

while($row = $result_chart->fetch_assoc()){
    $chart_data[] = [$row['category'], (float)$row['total']];
}

/* MONTHLY DATA */
$monthly_data = [];
$sql_month = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total 
              FROM expenses 
              GROUP BY month 
              ORDER BY month";

$result_month = $conn->query($sql_month);

while($row = $result_month->fetch_assoc()){
    $monthly_data[] = [$row['month'], (float)$row['total']];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Finsight Dashboard</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawMonthlyChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
['Category', 'Amount'],
<?php
foreach($chart_data as $c){
echo "['".$c[0]."', ".$c[1]."],";
}
?>
]);

var options = {
title: 'Expenses by Category',
pieHole: 0.4,
width: 500,
height: 400
};

var chart = new google.visualization.PieChart(document.getElementById('piechart'));
chart.draw(data, options);
}

function drawMonthlyChart() {

var data = google.visualization.arrayToDataTable([
['Month','Expense'],
<?php
foreach($monthly_data as $m){
echo "['".$m[0]."',".$m[1]."],";
}
?>
]);

var options = {
title: 'Monthly Expense Report',
width: 600,
height: 400,
legend: { position: 'none' }
};

var chart = new google.visualization.ColumnChart(document.getElementById('monthly_chart'));
chart.draw(data, options);
}
</script>

</head>

<body>

<h1>💰 Finsight Expense Dashboard</h1>

<h2>Budget: ₹ <?php echo $budget_amount; ?></h2>
<h2>Total Expense: ₹ <?php echo $total_expense; ?></h2>

<?php
if($remaining_budget < 0){
    echo "<h3 style='color:red;'>⚠ Budget Exceeded by ₹".abs($remaining_budget)."</h3>";
}else{
    echo "<h3>Remaining Budget: ₹ $remaining_budget</h3>";
}
?>

<hr>

<h2>📊 Category Wise Expenses</h2>
<div id="piechart"></div>

<h2>📈 Monthly Expenses</h2>
<div id="monthly_chart"></div>

<hr>

<h2>📋 Expense List</h2>

<table border="1" cellpadding="10">
<tr>
<th>ID</th>
<th>Amount</th>
<th>Category</th>
<th>Date</th>
<th>Description</th>
<th>Edit</th>
<th>Delete</th>
</tr>

<?php
if($result_expense->num_rows > 0){
    while($row = $result_expense->fetch_assoc()){
        echo "<tr>
        <td>".$row['id']."</td>
        <td>".$row['amount']."</td>
        <td>".$row['category']."</td>
<td>".$row['date']."</td>
        <td>".$row['description']."</td>
        <td><a href='edit_expense.php?id=".$row['id']."'>Edit</a></td>
        <td><a href='delete_expense.php?id=".$row['id']."' onclick=\"return confirm('Delete this expense?')\">Delete</a></td>
        </tr>";
    }
}else{
    echo "<tr><td colspan='7'>No expenses found</td></tr>";
}
?>

</table>

<br>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>