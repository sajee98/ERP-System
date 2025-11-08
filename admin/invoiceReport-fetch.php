<?php
require '../config/function.php'; 
header('Content-Type: application/json'); 

if (!isset($conn)) {
    echo '<tr><td colspan="7" class="text-danger text-center">Database connection error</td></tr>';
    exit;
}

$query = "SELECT * FROM invoices";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed to fetch customers</td></tr>';
    exit;
}
$rowsHtml = '';

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id'];
    $invoiceNo = htmlspecialchars($row['invoice_number']);
    $invoiceDate = htmlspecialchars($row['invoice_date']);
    $CusName = htmlspecialchars($row['customer_name']);
    $district = htmlspecialchars($row['district']);
    $qty = htmlspecialchars($row['quantity']);
    $total = htmlspecialchars($row['total']);

    $rowsHtml .= "<tr>
        <td>{$id}</td>
        <td>{$invoiceNo}</td>
        <td>{$invoiceDate}</td>
        <td>{$CusName}</td>
        <td>{$district}</td>
        <td>{$qty}</td>
        <td>{$total}</td>
        <td>
            <a href=\"customer-edit.php?id={$id}\" class=\"btn btn-sm btn-primary\">Edit</a>
            <a href=\"customer-delete.php?id={$id}\" class=\"btn btn-sm mx-2 btn-danger\">Delete</a>
        </td>
    </tr>";

}

if ($rowsHtml === '') {
    echo '<tr><td colspan="7" class="text-center">No customers found</td></tr>';
} else {
    echo $rowsHtml;
}
