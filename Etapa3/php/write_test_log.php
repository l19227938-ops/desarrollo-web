<?php
// write_test_log.php - prueba rápida de escritura de logs
header('Content-Type: application/json; charset=utf-8');
$base = __DIR__ . '/';
$results = [];

// Intenta escribir en debug.log (existente) y en test_write.log (nuevo)
$files = ['debug.log', 'test_write.log'];
foreach ($files as $f) {
    $path = $base . $f;
    $ok = @file_put_contents($path, date('[Y-m-d H:i:s] ') . "write_test\n", FILE_APPEND);
    $results[$f] = [
        'written' => $ok !== false,
        'bytes' => $ok === false ? null : $ok,
        'exists' => file_exists($path),
        'mtime' => file_exists($path) ? filemtime($path) : null,
        'size' => file_exists($path) ? filesize($path) : null,
    ];
}

// Also try error_log which goes to server error log
error_log("[write_test] writing via error_log OK");

echo json_encode(['ok' => true, 'results' => $results]);
?>
