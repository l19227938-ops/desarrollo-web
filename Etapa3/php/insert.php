<?php
// insert.php
// Recibe datos POST desde el formulario de contacto y los guarda en la tabla `contactos` de la base de datos.
// Cumple con la pauta: recibe datos, guarda en BD y responde en JSON para notificar al usuario.

// Si acceden directamente a insert.php, redirigir a index.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conex.php';

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

// Validación de campos obligatorios
if (!$nombre || !$correo || !$mensaje) {
  echo json_encode(['success' => false, 'error' => 'Faltan campos obligatorios']);
  exit;
}

// Validar que no existan dos personas con el mismo correo o teléfono
$query = "SELECT id FROM contactos WHERE correo = ? OR (telefono <> '' AND telefono = ? ) LIMIT 1";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("ss", $correo, $telefono);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Ya existe una persona con ese correo o teléfono.']);
        $stmt->close();
        exit;
    }
    $stmt->close();
}


// Usar mysqli para insertar datos
$stmt = $conn->prepare("INSERT INTO contactos (nombre, correo, telefono, mensaje, created_at) VALUES (?, ?, ?, ?, NOW())");
if ($stmt) {
    $stmt->bind_param("ssss", $nombre, $correo, $telefono, $mensaje);
    if ($stmt->execute()) {
        $insertId = $stmt->insert_id;
        echo json_encode(['success' => true, 'insertId' => $insertId]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
