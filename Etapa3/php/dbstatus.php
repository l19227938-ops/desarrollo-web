<?php
// dbstatus.php - quick diagnostic endpoint for DB connectivity
header('Content-Type: application/json; charset=utf-8');

function dbg($m) {
    @file_put_contents(__DIR__ . '/debug.log', date('[Y-m-d H:i:s] ') . $m . PHP_EOL, FILE_APPEND);
}
dbg('dbstatus.php start');

// Minimal config (same as conex.php)
$host = 'mysql.inf.uct.cl';
$user = 'edominguez';
$pass = 'EZp9LISppAfMhNY9E';
$db   = 'A2023_edominguez';

if (!class_exists('mysqli')) {
    dbg('dbstatus: mysqli extension missing');
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'mysqli_missing']);
    exit;
}

$mi = mysqli_init();
if (!$mi) {
    dbg('dbstatus: mysqli_init failed');
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'init_failed']);
    exit;
}

// short connect timeout (seconds)
$timeout = 3;
@$mi->options(MYSQLI_OPT_CONNECT_TIMEOUT, $timeout);

// Attempt to connect quickly
if (@$mi->real_connect($host, $user, $pass, $db)) {
    $server_info = $mi->server_info ?? null;
    $mi->close();
    dbg('dbstatus: connected ok; server_info=' . ($server_info ?? '-'));
    echo json_encode(['ok' => true, 'server_info' => $server_info]);
    exit;
} else {
    $err = $mi->connect_error ?? 'unknown';
    dbg('dbstatus: connect failed: ' . $err);
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'connect_failed', 'message' => $err]);
    exit;
}

?>
