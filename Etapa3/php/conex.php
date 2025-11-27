<?php

$servername = "mysql.inf.uct.cl";
$username = "edominguez";
$password = "EZp9LISppAfMhNY9E";
$dbname = "A2023_edominguez";

// Helper debug logger
function _dbg_conn($m) {
    @file_put_contents(__DIR__ . '/debug.log', date('[Y-m-d H:i:s] ') . $m . PHP_EOL, FILE_APPEND);
}

// Check that mysqli extension exists to avoid fatal errors
if (!class_exists('mysqli')) {
    _dbg_conn('mysqli extension not available in PHP - cannot create DB connection');
    // Do not output or exit here; set $conn to null and let the caller handle failures.
    $conn = null;
    return;
}

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    _dbg_conn('DB connection failed: ' . $conn->connect_error);
    // Do not die/exit here; set $conn to null and return so callers can respond correctly.
    $conn = null;
    return;
}
?>
