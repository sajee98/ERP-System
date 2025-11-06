<?php 
require '../config/function.php';

$paramResult = checkParamId('id');
if(is_numeric($paramResult)){
    $customerId = validate($paramResult);

       $customer = getById('customers', $customerId);
       if($customer['status'] == 200){
$cusDeleteRes = deleteQuery('customers', $customerId);
if($cusDeleteRes){
                redirect('customers.php', 'Customer Deleted Successfully');

       }else{
                redirect('customers.php', 'Customer Deletion Failed', 'error');
       }
         }else{
              header("Location: customers.php");
         }
    
}
else{
    header('customers.php', $paramResult);
}

