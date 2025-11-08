<?php
require '../config/function.php';
header('Content-Type: application/json; charset=utf-8');




if(isset($_POST['updateItem'])) {
    $id = validate($_POST['id']);
    $itemCode = validate($_POST['item_code']);
    $itemName = validate($_POST['item_name']);
    $itemCat = validate($_POST['item_category']);
    $itemSubcat = validate($_POST['item_sub_category']);
    $qty = validate($_POST['quantity']);
    $uPrice = validate($_POST['unit_price']);
    $tPrice = validate($_POST['total_price']);

    if($id && $itemCode && $itemName && $itemCat && $itemSubcat && $qty &&$uPrice &&$tPrice) {
        $sql = "UPDATE items SET item_code=?, item_name=?, item_category=?, item_sub_category=?, quantity=?, unit_price=?, total_price=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['status'=>'error','message'=>'SQL prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("ssssdddi", $itemCode, $itemName, $itemCat, $itemSubcat, $qty, $uPrice, $tPrice,  $id);

        if($stmt->execute()) {
            echo json_encode(['status'=>'success','message'=>'Customer updated successfully!']);
              header("Location: itemReport.php");
        } else {
            echo json_encode(['status'=>'error','message'=>'Database update failed: ' . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        echo json_encode(['status'=>'error','message'=>'Please fill all fields.']);
        exit;
    }
}
