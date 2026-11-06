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

// TCPDF (robust include)
require_once(__DIR__ . '/tcpdf.php');


// Fetch expenses
$stmt = $conn->prepare('SELECT id, amount, category, date, description FROM expenses WHERE user_id = ? ORDER BY date DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0.0;
$rowsHtml = '';

// Budget (same logic as dashboard)
$budget = 0;
$sql_budget = "SELECT amount FROM budget WHERE user_id=? AND category='Monthly' ORDER BY id DESC LIMIT 1";
if ($stmtBudget = $conn->prepare($sql_budget)) {
    $stmtBudget->bind_param('i', $user_id);
    $stmtBudget->execute();
    $resBudget = $stmtBudget->get_result();
    if ($resBudget && $resBudget->num_rows > 0) {
        $brow = $resBudget->fetch_assoc();
        $budget = (float)($brow['amount'] ?? 0);
    }
}


while ($result && ($row = $result->fetch_assoc())) {
    $amount = (float)($row['amount'] ?? 0);
    $total += $amount;

    $rowsHtml .= "<tr>
        <td>" . (int)($row['id'] ?? 0) . "</td>
        <td>₹" . htmlspecialchars(number_format($amount, 2, '.', '')) . "</td>
        <td>" . htmlspecialchars($row['category'] ?? '') . "</td>
        <td>" . htmlspecialchars($row['date'] ?? '') . "</td>
        <td>" . htmlspecialchars($row['description'] ?? '') . "</td>
    </tr>";
}

if ($rowsHtml === '') {
    $rowsHtml = "<tr><td colspan='5' style='text-align:center;'>No expenses found</td></tr>";
}

$generatedAt = date('Y-m-d H:i:s');

$brandTitle = "FIN SIGHT";
$schoolName = "Finsight Expense Tracker";

$html = "
<table width='100%' cellpadding='4' cellspacing='0'>
    <tr>
        <td width='70%' style='text-align:left;'>
            <div style='font-size:18px;font-weight:bold;color:#1f2937;'>" . htmlspecialchars($schoolName) . "</div>
            <div style='font-size:12px;color:#4b5563; margin-top:2px;'>" . htmlspecialchars($brandTitle) . " • Expense Report</div>
        </td>
        <td width='30%' style='text-align:right;'>
            <div style='font-size:12px;color:#4b5563;'>Generated: " . htmlspecialchars($generatedAt) . "</div>
            <div style='font-size:12px;color:#4b5563;'>User: " . htmlspecialchars($user_name) . "</div>
        </td>
    </tr>
</table>

<hr/>

<table width='100%' cellpadding='6' cellspacing='0'>
    <tr>
        <td width='33%' style='border:1px solid #e5e7eb;border-radius:8px;padding:10px;'>
            <b>Total Expense:</b> ₹" . htmlspecialchars(number_format($total, 2, '.', '')) . "
        </td>
        <td width='33%' style='border:1px solid #e5e7eb;border-radius:8px;padding:10px;'>
            <b>Monthly Budget:</b> ₹" . htmlspecialchars(number_format($budget ?? 0, 2, '.', '')) . "
        </td>
        <td width='33%' style='border:1px solid #e5e7eb;border-radius:8px;padding:10px;'>
            <b>Remaining:</b> ₹" . htmlspecialchars(number_format(($budget ?? 0) - $total, 2, '.', '')) . "
        </td>
    </tr>
</table>

<br/>

<table border='1' cellpadding='6' cellspacing='0' width='100%'>
<tr style='background-color:#4CAF50;color:#fff;font-weight:bold;'>
    <th width='10%'>ID</th>
    <th width='20%'>Amount</th>
    <th width='20%'>Category</th>
    <th width='20%'>Date</th>
    <th>Description</th>
</tr>
" . $rowsHtml . "
</table>

<br/>
<div style='font-size:12px;color:#6b7280;'>Note: This report is generated from the logged-in account data.</div>
";


$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Finsight');
$pdf->SetTitle('Expense Report');
$pdf->SetSubject('Expense Report');
$pdf->SetMargins(12, 12, 12);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

// Prevent partial output issues
ob_start();
$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();

// Output PDF
header('Content-Type: application/pdf');

header('Content-Disposition: attachment; filename="expense_report.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output('expense_report.pdf', 'D');

