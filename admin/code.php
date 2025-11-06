<?php
require '../config/function.php';
header('Content-Type: application/json; charset=utf-8');
if (isset($_POST['title'], $_POST['firstname'], $_POST['lastname'], $_POST['phone'], $_POST['district'])) {

    $title = validate($_POST['title']);
    $firstname = validate($_POST['firstname']);
    $lastname = validate($_POST['lastname']);
    $phone = validate($_POST['phone']);
    $district = validate($_POST['district']);

    if ($title != "" && $firstname != "" && $lastname != "" && $phone != "" && $district != "") {

        $insertQuery = "INSERT INTO customers (title, firstname, lastname, phoneNo, district) 
                        VALUES ('$title', '$firstname', '$lastname', '$phone', '$district')";
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



