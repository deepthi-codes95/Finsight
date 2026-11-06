<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


$user_id = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

require_once('tcpdf.php');

// Fetch expenses for this user
$stmt = $conn->prepare('SELECT id, amount, category, date, description FROM expenses WHERE user_id = ? ORDER BY date DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0.0;
$rowsHtml = '';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $amount = (float)($row['amount'] ?? 0);
        $total += $amount;

        $rowsHtml .= "<tr>
            <td>" . (int)$row['id'] . "</td>
            <td>₹" . htmlspecialchars(number_format($amount, 2, '.', '')) . "</td>
            <td>" . htmlspecialchars($row['category'] ?? '') . "</td>
            <td>" . htmlspecialchars($row['date'] ?? '') . "</td>
            <td>" . htmlspecialchars($row['description'] ?? '') . "</td>
        </tr>";
    }
} else {
    $rowsHtml = "<tr><td colspan='5' style='text-align:center;'>No expenses found</td></tr>";
}

$html = "
<h2 style='text-align:center;'>Finsight Expense Report</h2>
<p style='text-align:center;'>User: " . htmlspecialchars($user_name) . "</p>
<hr/>

<table border='1' cellpadding='6' cellspacing='0' width='100%'>
<tr style='background-color:#4CAF50;color:#fff;font-weight:bold;'>
    <th width='8%'>ID</th>
    <th width='18%'>Amount</th>
    <th width='18%'>Category</th>
    <th width='20%'>Date</th>
    <th>Description</th>
</tr>
" . $rowsHtml . "
</table>

<p style='margin-top:15px;font-size:14px;'><b>Total Expense:</b> ₹" . htmlspecialchars(number_format($total, 2, '.', '')) . "</p>
";

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Finsight');
$pdf->SetTitle('Expense Report');
$pdf->SetSubject('Expense Report');
$pdf->SetMargins(12, 12, 12);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$pdf->writeHTML($html, true, false, true, false, '');

// Force download (important headers so browser treats it as PDF)
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="expense_report.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
$pdf->Output('expense_report.pdf', 'D');


