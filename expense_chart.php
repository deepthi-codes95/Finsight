t<?php
$conn = new mysqli("127.0.0.1","root","","expense_db");

$sql = "SELECT category, SUM(amount) as total FROM expenses GROUP BY category";
$result = $conn->query($sql);

$categories = [];
$amounts = [];

while($row = $result->fetch_assoc()){
    $categories[] = $row['category'];
    $amounts[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Expense Chart</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<h2>Expense Chart by Category</h2>

<canvas id="expenseChart" width="400" height="200"></canvas>

<script>
var ctx = document.getElementById('expenseChart').getContext('2d');

var chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($categories); ?>,
        datasets: [{
            label: 'Expenses',
            data: <?php echo json_encode($amounts); ?>
        }]
    }
});
</script>

</body>
</html>