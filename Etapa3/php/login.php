<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// resto del código...

// login.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conex.php';
session_start();

// Simple debug logger (append-only)
function dbg($m) {
    @file_put_contents(__DIR__ . '/debug.log', date('[Y-m-d H:i:s] ') . $m . PHP_EOL, FILE_APPEND);
}
dbg('login.php start');

// If DB connection is missing (conex.php set $conn = null), return JSON 500 so client can parse it.
if (!isset($conn) || !$conn) {
    dbg('no DB connection available in conex.php');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB no disponible']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
    if (strpos($accept, 'application/json') !== false) {
        echo json_encode(['success' => false, 'error' => 'Método no permitido']);
        exit;
    }
    header('Location: ../index.php');
    exit;
}

$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
dbg('received POST, correo=' . substr($correo,0,64));

// Support JSON request bodies: if client sent application/json, parse and merge into _POST
$contentType = isset($_SERVER['CONTENT_TYPE']) ? strtolower(trim($_SERVER['CONTENT_TYPE'])) : '';
if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (is_array($json)) {
        // populate local variables from JSON if not set
        if (empty($correo) && isset($json['correo'])) $correo = trim($json['correo']);
        if (empty($password) && isset($json['password'])) $password = $json['password'];
        // also merge to $_POST for compatibility
        $_POST = array_merge($_POST, $json);
        dbg('parsed JSON input for login');
    }
}

if (!$correo || !$password) {
    echo json_encode(['success' => false, 'error' => 'Faltan campos']);
    exit;
}

$stmt = $conn->prepare('SELECT id, nombre, password_hash FROM users WHERE correo = ? LIMIT 1');
if ($stmt) {
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $user = null;
    // Use get_result() when available (requires mysqlnd); otherwise fall back to bind_result/fetch
    if (method_exists($stmt, 'get_result')) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        // Fallback: bind results manually
        $stmt->bind_result($f_id, $f_nombre, $f_password_hash);
        if ($stmt->fetch()) {
            $user = ['id' => $f_id, 'nombre' => $f_nombre, 'password_hash' => $f_password_hash];
        }
    }
    $stmt->close();
    dbg('db query executed, user found=' . ($user ? '1' : '0'));
    if ($user && password_verify($password, $user['password_hash'])) {
        // Login OK
        dbg('password verified for user id=' . $user['id']);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        echo json_encode(['success' => true, 'user' => ['id' => $user['id'], 'nombre' => $user['nombre']]]);
        dbg('login success responded');
        exit;
    } else {
        dbg('invalid credentials');
        echo json_encode(['success' => false, 'error' => 'Credenciales inválidas']);
        exit;
    }
} else {
    dbg('db prepare failed: ' . $conn->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
dbg('login.php end');
?>
