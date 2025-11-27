<?php
// session.php
// Devuelve el estado de la sesión (JSON)
header('Content-Type: application/json; charset=utf-8');
session_start();
$user = null;
if (isset($_SESSION['user_id'])) {
    $user = [
        'id' => $_SESSION['user_id'],
        'nombre' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null
    ];
}
echo json_encode(['user' => $user]);
?>
