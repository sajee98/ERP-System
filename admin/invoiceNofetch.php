<?php
require '../config/function.php'; 

header('Content-Type: application/json; charset=utf-8');

try {
    $query = "SELECT invoice_number FROM invoices ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'last_invoice' => $row['invoice_number']]);
    } else {
        echo json_encode(['status' => 'empty', 'last_invoice' => null]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
