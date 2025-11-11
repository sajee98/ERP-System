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
    $invoiceQty = (int) validate($_POST['quantity']);
    $unit_price = (float) validate($_POST['unit_price']);
    $total = (float) validate($_POST['total']);

    if ($invoiceNo !== "" && $invoiceDate !== "" && $customer_name !== "" && $district !== "" && $item_code !== "" && $item_name !== "" && $item_category !== "" && $item_sub_category !== "" && $invoiceQty > 0 && $unit_price >= 0 && $total >= 0) {

        mysqli_begin_transaction($conn);

        try {
            $insertInvoiceSql = "INSERT INTO invoices (invoice_number, invoice_date, customer_name, district, item_code, item_name, item_category, item_sub_category, quantity, unit_price, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertInvoiceSql);
            if (!$stmt) {
                throw new Exception('Prepare failed for invoice insert: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ssssssssidd', $invoiceNo, $invoiceDate, $customer_name, $district, $item_code, $item_name, $item_category, $item_sub_category, $invoiceQty, $unit_price, $total);
            $exec = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if (!$exec) {
                throw new Exception('Failed to insert invoice: ' . mysqli_error($conn));
            }

            $selectItemSql = "SELECT id, quantity FROM items WHERE item_code = ? AND item_name = ? AND item_category = ? AND item_sub_category = ? LIMIT 1 FOR UPDATE";
            $stmt = mysqli_prepare($conn, $selectItemSql);
            if (!$stmt) {
                throw new Exception('Prepare failed for item select: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ssss', $item_code, $item_name, $item_category, $item_sub_category);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $itemId, $existingQty);
            $found = mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if (!$found) {
                mysqli_rollback($conn);
                echo json_encode(['status' => 'error', 'message' => 'No stock.']);
                exit;
            }

            //  stock availability
            if ($existingQty < $invoiceQty) {
                mysqli_rollback($conn);
                echo json_encode(['status' => 'error', 'message' => 'No stock.', 'available_quantity' => (int)$existingQty]);
                exit;
            }

            $newQty = $existingQty - $invoiceQty;
            $updateSql = "UPDATE items SET quantity = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $updateSql);
            if (!$stmt) {
                throw new Exception('Prepare failed for item update: ' . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'ii', $newQty, $itemId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if (!$ok) {
                throw new Exception('Failed to update item quantity: ' . mysqli_error($conn));
            }

            mysqli_commit($conn);

            $resp = [
                'status' => 'success',
                'message' => 'invoice billed',
                'item_id' => (int)$itemId,
                'old_quantity' => (int)$existingQty,
                'new_quantity' => (int)$newQty,
                'out_of_stock' => $newQty === 0
            ];
            if ($newQty === 0) {
                $resp['note'] = 'Item is now out of stock.';
            }
            echo json_encode($resp);
            
            exit;

        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields correctly.']);
        exit;
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid form submission.']);
    exit;
}
