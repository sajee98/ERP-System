<?php
require_once __DIR__ . '/../config/function.php'; 

header('Content-Type: application/json; charset=utf-8');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Invalid request method. Use POST.']);
    exit;
}

// fallback sanitize if validate() missing
if (!function_exists('validate')) {
    function validate($v) {
        return trim(htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'));
    }
}



$required = ['item_code','item_name','item_category','item_sub_category','quantity','unit_price','total_price'];
$errors = [];
foreach ($required as $f) {
    if (!isset($_POST[$f]) || trim($_POST[$f]) === '') {
        $errors[$f] = 'This field is required';
    }
}
if (!empty($errors)) {
    echo json_encode(['status'=>'error','message'=>'Please fill all fields.','errors'=>$errors]);
    exit;
}

// sanitize
$itemCode   = validate($_POST['item_code']);
$itemName   = validate($_POST['item_name']);
$itemCat    = validate($_POST['item_category']);
$itemSubCat = validate($_POST['item_sub_category']);
$quantity   = validate($_POST['quantity']);
$Uprice     = validate($_POST['unit_price']);
$Tprice     = validate($_POST['total_price']);

// cast numeric types
$quantityInt  = (int)$quantity;
$UpriceFloat  = (float)$Uprice;
$TpriceFloat  = (float)$Tprice;

// further validation
if (!is_numeric($quantity) || $quantityInt < 0) $errors['quantity'] = 'Quantity must be 0 or greater';
if (!is_numeric($Uprice) || $UpriceFloat < 0) $errors['unit_price'] = 'Unit price must be 0 or greater';
if (!is_numeric($Tprice) || $TpriceFloat < 0) $errors['total_price'] = 'Total price must be 0 or greater';

if (!empty($errors)) {
    echo json_encode(['status'=>'error','message'=>'Validation failed.','errors'=>$errors]);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $selSql = "SELECT id, quantity, unit_price, total_price FROM items WHERE item_code = ? AND item_name = ? AND item_category = ? LIMIT 1";
    $selStmt = mysqli_prepare($conn, $selSql);
    if (!$selStmt) {
        throw new Exception('Failed to prepare select statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($selStmt, "sss", $itemCode, $itemName, $itemCat);
    mysqli_stmt_execute($selStmt);
    $res = mysqli_stmt_get_result($selStmt);
    $existing = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($selStmt);

    if ($existing) {
        $existingQty   = (int)$existing['quantity'];
        $existingTotal = (float)$existing['total_price'];
        $existingId    = (int)$existing['id'];

        $newQty   = $existingQty + $quantityInt;
        $newTotal = $existingTotal + $TpriceFloat;

        // avoid div by zero
        $newUnitPrice = ($newQty > 0) ? ($newTotal / $newQty) : 0.0;

        $updateSql = "UPDATE items SET item_sub_category = ?, quantity = ?, unit_price = ?, total_price = ? WHERE id = ?";
        $updStmt = mysqli_prepare($conn, $updateSql);
        if (!$updStmt) {
            throw new Exception('Failed to prepare update statement: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($updStmt, "siddi", $itemSubCat, $newQty, $newUnitPrice, $newTotal, $existingId);

        if (!mysqli_stmt_execute($updStmt)) {
            $err = mysqli_stmt_error($updStmt);
            mysqli_stmt_close($updStmt);
            throw new Exception('Failed to execute update: ' . $err);
        }
        mysqli_stmt_close($updStmt);

        mysqli_commit($conn);
        echo json_encode([
            'status' => 'success',
            'message' => 'items are updated',
            'data' => [
                'id' => $existingId,
                'item_code' => $itemCode,
                'item_name' => $itemName,
                'item_category' => $itemCat,
                'item_sub_category' => $itemSubCat,
                'quantity' => $newQty,
                'unit_price' => $newUnitPrice,
                'total_price' => $newTotal
            ]
        ]);
        exit;
    } else {
        $insertSql = "INSERT INTO items (item_code, item_name, item_category, item_sub_category, quantity, unit_price, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insStmt = mysqli_prepare($conn, $insertSql);
        if (!$insStmt) {
            throw new Exception('Failed to prepare insert statement: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($insStmt, "ssssidd", $itemCode, $itemName, $itemCat, $itemSubCat, $quantityInt, $UpriceFloat, $TpriceFloat);

        if (!mysqli_stmt_execute($insStmt)) {
            $err = mysqli_stmt_error($insStmt);
            mysqli_stmt_close($insStmt);
            throw new Exception('Failed to execute insert: ' . $err);
        }
        $newId = mysqli_insert_id($conn);
        mysqli_stmt_close($insStmt);

        mysqli_commit($conn);
        echo json_encode([
            'status' => 'success',
            'message' => 'Item saved successfully!',
            'data' => [
                'id' => $newId,
                'item_code' => $itemCode,
                'item_name' => $itemName,
                'item_category' => $itemCat,
                'item_sub_category' => $itemSubCat,
                'quantity' => $quantityInt,
                'unit_price' => $UpriceFloat,
                'total_price' => $TpriceFloat
            ]
        ]);
        exit;
    }

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status'=>'error','message'=>'Database error: '.$e->getMessage()]);
    exit;
}
