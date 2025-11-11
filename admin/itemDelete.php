<?php
header('Content-Type: application/json; charset=utf-8');
require '../config/function.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing id']);
    exit;
}

$paramResult = checkParamId('id', $data['id'] ?? null); 
$itemId = validate($data['id']);

if (!is_numeric($itemId)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid id']);
    exit;
}

$item = getById('items', $itemId);
if ($item['status'] != 200) {
    echo json_encode(['status' => 'error', 'message' => 'Item not found']);
    exit;
}

$ItemDeleteRes = deleteQuery('items', $itemId);
if ($ItemDeleteRes) {
    echo json_encode(['status' => 'success', 'message' => 'Item Deleted Successfully']);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Item Deletion Failed']);
    exit;
}
