<?php
// register_test.php - temporary diagnostic endpoint (remove after debugging)
header('Content-Type: application/json; charset=utf-8');
$input = file_get_contents('php://input');
$parsed = [];
parse_str($input, $parsed);
echo json_encode(['ok' => true, 'method' => $_SERVER['REQUEST_METHOD'], 'body_raw' => $input, 'parsed' => $parsed]);
?>
