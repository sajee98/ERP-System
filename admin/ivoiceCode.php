<?php
require '../config/function.php';
header('Content-Type: application/json; charset=utf-8');
if (isset($_POST['invoice_number'], $_POST['invoice_date'], $_POST['customer_name'], $_POST['district'], $_POST['item_code'], $_POST['item_name'], $_POST['item_category'], $_POST['item_sub_category'], $_POST['quantity'], $_POST['unit_price'], $_POST['total'])) {

    $invoiceNo = validate($_POST['invoice_number']);
    $invoiceDate = validate($_POST['invoice_date']);
    $customer_name = validate($_POST['customer_name']);
    $district = validate($_POST['district']);
    $item_code = validate($_POST['item_code']);
    $item_name = validate($_POST['item_name']);
    $item_category = validate($_POST['item_category']);
    $item_sub_category = validate($_POST['item_sub_category']);
    $quantity = validate($_POST['quantity']);
    $unit_price = validate($_POST['unit_price']);
    $total = validate($_POST['total']);

    
    

  if ($invoiceNo != "" && $invoiceDate != "" && $customer_name != "" && $district != "" && $item_code != "" &&$item_name != "" &&$item_category != "" &&$item_sub_category != ""&&$quantity != ""&&$unit_price != ""&&$total != "" ) {

        // Make sure columns match the values you're inserting.
        $insertQuery = "INSERT INTO invoices
            (invoice_number, invoice_date, customer_name, district, item_code, item_name, item_category, item_sub_category, quantity, unit_price, total)
            VALUES
            ('$invoiceNo', '$invoiceDate', '$customer_name', '$district', '$item_code', '$item_name', '$item_category', '$item_sub_category', '$quantity', '$unit_price', '$total')";

        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo json_encode(['status' => 'success', 'message' => 'Customer saved successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save customer.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid form submission.']);
}
