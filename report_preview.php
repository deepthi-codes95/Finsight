<?php
session_start();
require_once('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Fetch expenses for this user
$stmt = $conn->prepare('SELECT id, amount, category, date, description FROM expenses WHERE user_id = ? ORDER BY date DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0.0;
$rows = [];

while ($result && ($row = $result->fetch_assoc())) {
    $amount = (float)($row['amount'] ?? 0);
    $total += $amount;
    $rows[] = [
        'id' => (int)($row['id'] ?? 0),
        'amount' => $amount,
        'category' => (string)($row['category'] ?? ''),
        'date' => (string)($row['date'] ?? ''),
        'description' => (string)($row['description'] ?? ''),
    ];
}

// Optional: show budget/remaining like dashboard does
$sql_budget = "SELECT amount FROM budget WHERE user_id=? AND category='Monthly' ORDER BY id DESC LIMIT 1";
$budget = 0;
if ($stmt2 = $conn->prepare($sql_budget)) {
    $stmt2->bind_param('i', $user_id);
    $stmt2->execute();
    $res = $stmt2->get_result();
    if ($res && $res->num_rows > 0) {
        $brow = $res->fetch_assoc();
        $budget = (float)($brow['amount'] ?? 0);
    }
}
$remaining = $budget - $total;
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Expense Report Preview</title>
    <style>
        body{font-family:Arial;background:#f4f6f9;margin:0;padding:20px;}
        .top{max-width:1100px;margin:0 auto;background:#fff;border-radius:12px;padding:20px;}
        h2{text-align:center;margin:0 0 10px;}
        .meta{text-align:center;color:#333;margin-bottom:14px;}
        .cards{display:flex;gap:12px;justify-content:center;margin:12px 0;flex-wrap:wrap;}
        .card{background:#eef2ff;border-radius:12px;padding:12px 18px;min-width:220px;text-align:center;}
        table{width:100%;border-collapse:collapse;background:#fff;margin-top:10px;}
        th,td{border:1px solid #d1d5db;padding:8px;text-align:center;font-size:14px;}
        th{background:#111827;color:#fff;}
        .btns{text-align:center;margin-top:14px;}
        a.btn{display:inline-block;padding:10px 16px;background:#2563eb;color:#fff;text-decoration:none;border-radius:8px;}
        a.btn.secondary{background:#6b7280;}
        .empty{text-align:center;padding:14px;color:#6b7280;}
    </style>
</head>
<body>
<div class="top">
    <h2>Finsight Expense Report</h2>
    <div class="meta">User: <?= htmlspecialchars($user_name) ?></div>

    <div class="cards">
        <div class="card"><b>Total:</b> ₹ <?= number_format($total, 2) ?></div>
        <div class="card"><b>Budget:</b> ₹ <?= number_format($budget, 2) ?></div>
        <div class="card"><b>Remaining:</b> ₹ <?= number_format($remaining, 2) ?></div>
    </div>

    <div style="text-align:center;color:#6b7280;font-size:12px;margin-top:8px;">
        Generated: <?= htmlspecialchars(date('Y-m-d H:i:s')) ?> • Report by Finsight
    </div>


    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Date</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($rows) === 0): ?>
            <tr><td colspan="5" class="empty">No expenses found</td></tr>
        <?php else: ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td>₹ <?= number_format($r['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($r['category']) ?></td>
                    <td><?= htmlspecialchars($r['date']) ?></td>
                    <td><?= htmlspecialchars($r['description']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="btns">
        <a class="btn" href="download_report_v2.php">Download PDF</a>
        <a class="btn secondary" href="dashboard.php">Back</a>
    </div>
</div>
</body>
</html>

