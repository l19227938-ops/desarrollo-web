<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conex.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, nombre, correo, telefono, mensaje, created_at FROM contactos WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($row);
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
    }
} else {
    $sql = "SELECT id, nombre, correo, telefono, mensaje, created_at FROM contactos ORDER BY created_at DESC LIMIT 100";
    $result = $conn->query($sql);
    if ($result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($rows);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $conn->error]);
    }
}
