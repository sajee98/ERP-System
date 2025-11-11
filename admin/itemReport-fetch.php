<?php
require '../config/function.php'; 

header('Content-Type: text/html; charset=utf-8');



$from = isset($_GET['from']) && $_GET['from'] !== '' ? $_GET['from'] : null;
$to   = isset($_GET['to']) && $_GET['to'] !== '' ? $_GET['to'] : null;
$search = isset($_GET['search']) && trim($_GET['search']) !== '' ? trim($_GET['search']) : null;

$where = [];
$params = [];
$types = '';

if ($from) {
    $where[] = "DATE(created_at) >= ?";
    $params[] = $from;
    $types .= 's';
}
if ($to) {
    $where[] = "DATE(created_at) <= ?";
    $params[] = $to;
    $types .= 's';
}
if ($search) {
    $where[] = "(item_name LIKE ? OR item_category LIKE ? OR item_sub_category LIKE ? OR item_code LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'ssss';
}

$sql = "SELECT id, item_name, item_category, item_sub_category, quantity, DATE(created_at) as added_date FROM items";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY id DESC LIMIT 1000"; 

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed to prepare query</td></tr>';
    exit;
}

if (count($params) > 0) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

if (!mysqli_stmt_execute($stmt)) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed  execute query</td></tr>';
    exit;
}

$result = mysqli_stmt_get_result($stmt);
if ($result === false) {
    echo '<tr><td colspan="7" class="text-danger text-center">Error fetching results</td></tr>';
    exit;
}

$rowsHtml = '';
while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id'];
    $itemName = htmlspecialchars($row['item_name']);
    $itemCat = htmlspecialchars($row['item_category']);
    $itemSubCat = htmlspecialchars($row['item_sub_category']);
    $qty = htmlspecialchars($row['quantity']);
    $added = htmlspecialchars($row['added_date'] ?? '');

    $rowsHtml .= "<tr>
        <td>{$id}</td>
        <td>{$itemName}</td>
        <td>{$itemCat}</td>
        <td>{$itemSubCat}</td>
        <td>{$qty}</td>
        <td>{$added}</td>
        <td>
            <a href=\"item-edit.php?id={$id}\" class=\"btn btn-sm btn-primary\">Edit</a>
            <button class=\"btn btn-sm mx-2 btn-danger btn-delete\" data-id=\"{$id}\">Delete</button>
        </td>
    </tr>";
}

if ($rowsHtml === '') {
    echo '<tr><td colspan="7" class="text-center">No items found</td></tr>';
} else {
    echo $rowsHtml;
}
