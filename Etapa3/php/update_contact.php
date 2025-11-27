<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conex.php';
session_start();

// Only logged-in users can update contacts
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit;
}

// Accept JSON or form-encoded
$contentType = isset($_SERVER['CONTENT_TYPE']) ? strtolower(trim($_SERVER['CONTENT_TYPE'])) : '';
if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
} else {
    $data = $_POST;
}

$id = isset($data['id']) ? intval($data['id']) : 0;
$nombre = isset($data['nombre']) ? trim($data['nombre']) : '';
$correo = isset($data['correo']) ? trim($data['correo']) : '';
$telefono = isset($data['telefono']) ? trim($data['telefono']) : '';
$mensaje = isset($data['mensaje']) ? trim($data['mensaje']) : '';

if ($id <= 0 || !$nombre || !$correo || !$mensaje) {
    echo json_encode(['success' => false, 'error' => 'Faltan campos o id inválido']);
    exit;
}

$stmt = $conn->prepare("UPDATE contactos SET nombre = ?, correo = ?, telefono = ?, mensaje = ? WHERE id = ?");
if ($stmt) {
    $stmt->bind_param('ssssi', $nombre, $correo, $telefono, $mensaje, $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>
