<?php
// register_probe.php - diagnostic probe for register flow (temporary)
header('Content-Type: application/json; charset=utf-8');
$log = __DIR__ . '/register_probe.log';
@file_put_contents($log, date('[Y-m-d H:i:s] ') . "probe start\n", FILE_APPEND);

// Include DB connection
require_once __DIR__ . '/conex.php';
@file_put_contents($log, date('[Y-m-d H:i:s] ') . "after conex, conn set? " . (isset($conn) && $conn ? 'yes' : 'no') . "\n", FILE_APPEND);

if (!isset($conn) || !$conn) {
    @file_put_contents($log, date('[Y-m-d H:i:s] ') . "probe: no DB connection\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'no_db']);
    exit;
}

// Try a simple query
if ($res = $conn->query('SELECT 1 AS one')) {
    $row = $res->fetch_assoc();
    @file_put_contents($log, date('[Y-m-d H:i:s] ') . "probe: query ok, row=" . json_encode($row) . "\n", FILE_APPEND);
    echo json_encode(['ok' => true, 'one' => $row['one']]);
    $res->free();
    exit;
} else {
    @file_put_contents($log, date('[Y-m-d H:i:s] ') . "probe: query failed: " . $conn->error . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'query_failed', 'message' => $conn->error]);
    exit;
}

?>
