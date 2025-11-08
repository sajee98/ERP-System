<?php
require '../config/function.php'; 
header('Content-Type: application/json'); 

if (!isset($conn)) {
    echo '<tr><td colspan="7" class="text-danger text-center">Database connection error</td></tr>';
    exit;
}

$query = "SELECT * FROM customers";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo '<tr><td colspan="7" class="text-danger text-center">Failed to fetch customers</td></tr>';
    exit;
}

$rowsHtml = '';
$counter = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id'];
    $title = htmlspecialchars($row['title']);
    $firstname = htmlspecialchars($row['firstname']);
    $lastname = htmlspecialchars($row['lastname']);
    $phone = htmlspecialchars($row['phoneNo']);
    $district = htmlspecialchars($row['district']);

    $rowsHtml .= "<tr>
        <td>{$counter}</td>
        <td>{$id}</td>
        <td>{$title}</td>
        <td>{$firstname}</td>
        <td>{$lastname}</td>
        <td>{$phone}</td>
        <td>{$district}</td>
        <td>
            <a href=\"customer-edit.php?id={$id}\" class=\"btn btn-sm btn-primary\">Edit</a>
            <a href=\"customer-delete.php?id={$id}\" class=\"btn btn-sm mx-2 btn-danger \">Delete</a>
        </td>
    </tr>";
    $counter++;
}

if ($rowsHtml === '') {
    echo '<tr><td colspan="7" class="text-center">No customers found</td></tr>';
} else {
    echo $rowsHtml;
}
