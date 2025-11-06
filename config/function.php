<?php
require 'dbconnect.php';
session_start();

function validate($inputData) {

    global $conn;

    $validatedData = mysqli_real_escape_string($conn, trim($inputData));
    return trim($validatedData);
}

function redirect($url, $status)
{
    $_SESSION['status'] = $status;
    header('Location: '.$url);
    exit();
}

function getAll($tableName)
{

    global $conn;
    $table = validate($tableName);

        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);

        return $result;
}

function checkParamId($paramType){

    if(isset($_GET[$paramType])){
       if($_GET[$paramType] != null){
           return $_GET[$paramType];
    } else {
        return "No id fount";
    }
}
    else {
        return "No id fount";
    }
}

function getById($tableName, $id){
    global $conn;
    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){
        if(mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $response = [
           'status' => 200,
                'message' => 'Data found',
           'data' => $row
            ];
       return $response;

        }else{
            $response = [
           'status' => 404,
              'message' => 'Not data: '.$conn->error
       ];
       return $response;
        }
    }else {
       $response = [
           'status' => 500,
              'message' => 'Error: '.$conn->error
       ];
       return $response;
    }

  
}


//delete function
        function deleteQuery($tableName, $id){
            global $conn;
             $table = validate($tableName);
    $id = validate($id);
            $query = "DELETE FROM $table WHERE id='$id' ";
            $result = mysqli_query($conn, $query);
            return $result;
        }



?>
