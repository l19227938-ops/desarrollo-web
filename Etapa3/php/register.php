<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Registra un nuevo usuario (POST) y responde JSON.
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conex.php';
// Start session early to allow auto-login after successful registration
session_start();

// Simple debug logger (append-only)
function dbg($m) {
    // Use error_log to avoid writing into project files (may lack permissions).
    // This writes to the webserver/PHP error log or to the log configured by `error_log`.
    error_log(date('[Y-m-d H:i:s] ') . $m . PHP_EOL);
}
dbg('register.php start');

// Convert PHP warnings/notices to exceptions so we can return JSON on unexpected errors
set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function($e) {
    dbg('register exception: ' . $e->getMessage());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode(['success' => false, 'error' => 'exception', 'message' => $e->getMessage()]);
    exit;
});

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Log fatal errors to server error log to avoid permission issues writing project files.
        error_log(date('[Y-m-d H:i:s] ') . 'register fatal: ' . $err['message'] . PHP_EOL);
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(['success' => false, 'error' => 'fatal', 'message' => $err['message']]);
    }
});

// If DB connection is missing (conex.php set $conn = null), return JSON 500 so client can parse it.
if (!isset($conn) || !$conn) {
    dbg('no DB connection available in conex.php (register)');
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB no disponible']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If the client expects JSON (API call), return JSON error.
    $accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
    if (strpos($accept, 'application/json') !== false) {
        echo json_encode(['success' => false, 'error' => 'Método no permitido']);
        exit;
    }
    // Otherwise redirect browser back to site home to avoid showing raw JSON
    header('Location: ../index.php');
    exit;
}

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
dbg('received POST, nombre=' . substr($nombre,0,64) . ' correo=' . substr($correo,0,64));

// Support JSON request bodies: if client sent application/json, parse and merge into _POST
$contentType = isset($_SERVER['CONTENT_TYPE']) ? strtolower(trim($_SERVER['CONTENT_TYPE'])) : '';
if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (is_array($json)) {
        if (empty($nombre) && isset($json['nombre'])) $nombre = trim($json['nombre']);
        if (empty($correo) && isset($json['correo'])) $correo = trim($json['correo']);
        if (empty($password) && isset($json['password'])) $password = $json['password'];
        $_POST = array_merge($_POST, $json);
        dbg('parsed JSON input for register');
    }
}

if (!$nombre || !$correo || !$password) {
    echo json_encode(['success' => false, 'error' => 'Faltan campos']);
    exit;
}

// Verificar correo único
$stmt = $conn->prepare('SELECT id FROM users WHERE correo = ? LIMIT 1');
if ($stmt) {
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        dbg('register: correo already exists');
        echo json_encode(['success' => false, 'error' => 'Correo ya registrado']);
        $stmt->close();
        exit;
    }
    $stmt->close();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
// Insert without `created_at` column (some DB schemas may not have it)
dbg('register: before prepare insert');
$stmt = $conn->prepare('INSERT INTO users (nombre, correo, password_hash) VALUES (?, ?, ?)');
dbg('register: after prepare insert (stmt ' . ($stmt ? 'ok' : 'null') . ')');
if ($stmt) {
    $stmt->bind_param('sss', $nombre, $correo, $hash);
    dbg('register: before execute');
    if ($stmt->execute()) {
        dbg('register: after execute success');
        // Use connection insert_id to be safe
        dbg('register success id=' . $conn->insert_id);
        dbg('register: before response');
        // Auto-login: create session for newly registered user
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['user_name'] = $nombre;
        echo json_encode(['success' => true, 'userId' => $conn->insert_id, 'user' => ['id' => $conn->insert_id, 'nombre' => $nombre]]);
        dbg('register: response sent');
    } else {
        dbg('register execute failed: ' . $stmt->error);
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    dbg('register prepare failed: ' . $conn->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
dbg('register.php end');
?>
