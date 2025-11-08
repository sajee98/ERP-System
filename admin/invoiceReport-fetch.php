<?php
require '../config/function.php'; // connects $conn
header('Content-Type: text/html; charset=utf-8');



$from = isset($_GET['from']) && $_GET['from'] !== '' ? $_GET['from'] : null;
$to = isset($_GET['to']) && $_GET['to'] !== '' ? $_GET['to'] : null;
$search = isset($_GET['search']) && trim($_GET['search']) !== '' ? trim($_GET['search']) : null;

$where = [];
$params = [];
$types = '';

if ($from) {
    $where[] = "DATE(invoice_date) >= ?";
    $params[] = $from;
    $types .= 's';
}
if ($to) {
    $where[] = "DATE(invoice_date) <= ?";
    $params[] = $to;
    $types .= 's';
}
if ($search) {
    $where[] = "(invoice_number LIKE ? OR customer_name LIKE ? OR district LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

$sql = "SELECT id, invoice_number, invoice_date, customer_name, district, quantity, total FROM invoices";
if (count($where) > 0) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY invoice_date DESC LIMIT 1000";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed to prepare query</td></tr>';
    exit;
}

if (count($params) > 0) mysqli_stmt_bind_param($stmt, $types, ...$params);
if (!mysqli_stmt_execute($stmt)) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed to execute query</td></tr>';
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$rowsHtml = '';

while ($row = mysqli_fetch_assoc($result)) {
    $rowsHtml .= "<tr>
        <td>".(int)$row['id']."</td>
        <td>".htmlspecialchars($row['invoice_number'])."</td>
        <td>".htmlspecialchars($row['invoice_date'])."</td>
        <td>".htmlspecialchars($row['customer_name'])."</td>
        <td>".htmlspecialchars($row['district'])."</td>
        <td>".htmlspecialchars($row['quantity'])."</td>
        <td>".htmlspecialchars($row['total'])."</td>
    </tr>";
}

if ($rowsHtml === '') echo '<tr><td colspan="7" class="text-center">No invoices found</td></tr>';
else echo $rowsHtml;
