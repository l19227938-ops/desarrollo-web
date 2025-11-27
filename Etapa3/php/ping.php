<?php
// ping.php - endpoint de prueba rápido
header('Content-Type: text/plain; charset=utf-8');
// append simple log
@file_put_contents(__DIR__ . '/debug.log', date('[Y-m-d H:i:s] ') . "ping received" . PHP_EOL, FILE_APPEND);
echo "OK";
?>
