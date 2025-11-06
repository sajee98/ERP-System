<?php
require '../config/function.php';
header('Content-Type: application/json; charset=utf-8');




if(isset($_POST['UpdateCustomer'])) {
    $id = validate($_POST['id']);
    $title = validate($_POST['title']);
    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $phone = validate($_POST['phone']);
    $district = validate($_POST['district']);

    if($id && $title && $firstname && $lastname && $phone && $district) {
        $sql = "UPDATE customers SET title=?, firstname=?, lastname=?, phoneNo=?, district=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode(['status'=>'error','message'=>'SQL prepare failed: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("sssssi", $title, $firstname, $lastname, $phone, $district, $id);

        if($stmt->execute()) {
            echo json_encode(['status'=>'success','message'=>'Customer updated successfully!']);
              header("Location: customers.php");
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
