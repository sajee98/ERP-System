<?php 
require '../config/function.php';

$paramResult = checkParamId('id');
if(is_numeric($paramResult)){
    $itemId = validate($paramResult);

       $item = getById('items', $itemId);
       if($item['status'] == 200){
$ItemDeleteRes = deleteQuery('items', $itemId);
if($ItemDeleteRes){
                redirect('itemReport.php', 'Item Deleted Successfully');

       }else{
                redirect('itemReport.php', 'item Deletion Failed', 'error');
       }
         }else{
              header("Location: itemReport.php");
         }
    
}
else{
    header('items.php', $paramResult);
}

