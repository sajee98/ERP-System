<?php
require '../config/function.php'; // ensures $conn
header('Content-Type: text/html; charset=utf-8');

if (!isset($conn) || !$conn) {
    echo '<tr><td colspan="6" class="text-danger text-center">Database connection error</td></tr>';
    exit;
}

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
    $where[] = "(invoice_number LIKE ? OR customer_name LIKE ? OR item_name LIKE ? OR item_category LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'ssss';
}

$sql = "SELECT invoice_number, invoice_date, customer_name, item_name, item_code, item_category, item_sub_category 
        FROM invoices";
if (count($where) > 0) $sql .= " WHERE " . implode(' AND ', $where);
$sql .= " ORDER BY invoice_date DESC LIMIT 1000";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo '<tr><td colspan="6" class="text-danger text-center">Failed to prepare query</td></tr>';
    exit;
}

if (count($params) > 0) mysqli_stmt_bind_param($stmt, $types, ...$params);
if (!mysqli_stmt_execute($stmt)) {
    echo '<tr><td colspan="6" class="text-danger text-center">Failed to execute query</td></tr>';
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$rowsHtml = '';

while ($row = mysqli_fetch_assoc($result)) {
    $rowsHtml .= "<tr>
        <td>".htmlspecialchars($row['invoice_number'])."</td>
        <td>".htmlspecialchars($row['invoice_date'])."</td>
        <td>".htmlspecialchars($row['customer_name'])."</td>
        <td>".htmlspecialchars($row['item_name'])."-".htmlspecialchars($row['item_code'])."</td>
        <td>".htmlspecialchars($row['item_category'])."</td>
        <td>".htmlspecialchars($row['item_sub_category'])."</td>
    </tr>";
}

if ($rowsHtml === '') echo '<tr><td colspan="6" class="text-center">No invoice items found</td></tr>';
else echo $rowsHtml;
