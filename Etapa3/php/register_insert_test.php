<?php
// register_insert_test.php - temporary diagnostic to test INSERT and report DB errors
header('Content-Type: text/plain; charset=utf-8');
echo "register_insert_test start\n";
require_once __DIR__ . '/conex.php';
if (!isset($conn) || !$conn) {
    echo "NO DB CONNECTION\n";
    exit;
}

$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : 'PruebaInsert';
$correo = isset($_GET['correo']) ? $_GET['correo'] : 'prueba_insert_' . time() . '@example.com';
$password = isset($_GET['password']) ? $_GET['password'] : '1234';

echo "Will insert nombre={$nombre}, correo={$correo}\n";

$hash = password_hash($password, PASSWORD_DEFAULT);

$n = $conn->real_escape_string($nombre);
$c = $conn->real_escape_string($correo);
$h = $conn->real_escape_string($hash);

$sql = "INSERT INTO users (nombre, correo, password_hash, created_at) VALUES ('{$n}', '{$c}', '{$h}', NOW())";
echo "SQL: " . $sql . "\n";
// Enable mysqli exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$logfile = __DIR__ . '/register_insert_test.log';
try {
    $res = $conn->query($sql);
    echo "Insert OK. insert_id=" . $conn->insert_id . " affected_rows=" . $conn->affected_rows . "\n";
    @file_put_contents($logfile, date('[Y-m-d H:i:s] ') . "Insert OK id=" . $conn->insert_id . "\n", FILE_APPEND);
} catch (Throwable $e) {
    $msg = 'Exception: ' . $e->getMessage();
    echo "Insert FAILED (exception).\n";
    echo $msg . "\n";
    @file_put_contents($logfile, date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

?>
