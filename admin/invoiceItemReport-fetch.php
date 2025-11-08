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
    $itemName = htmlspecialchars($row['item_name']);
    $itemCode = htmlspecialchars($row['item_code']);
    $itemCat = htmlspecialchars($row['item_category']);
    $itemSubCat = htmlspecialchars($row['item_sub_category']);

    $rowsHtml .= "<tr>
        <td>{$id}</td>
        <td>{$invoiceNo}</td>
        <td>{$invoiceDate}</td>
        <td>{$CusName}</td>
        <td>{$itemName}-{$itemCode}</td>
        <td>{$itemCat}</td>
        <td>{$itemSubCat}</td>
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
