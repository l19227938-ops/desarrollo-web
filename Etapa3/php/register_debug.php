<?php
// register_debug.php - plaintext diagnostic for register flow (open in browser)
header('Content-Type: text/plain; charset=utf-8');
echo "register_debug start\n";
echo "including conex.php...\n";
require_once __DIR__ . '/conex.php';
echo "after include.\n";

if (!isset($conn) || !$conn) {
    echo "NO DB CONNECTION\n";
    if (isset($conn) && $conn && method_exists($conn, 'connect_error')) {
        echo "conn error: " . $conn->connect_error . "\n";
    }
    exit;
}

echo "DB connection appears set. server_info: " . (method_exists($conn, 'server_info') ? $conn->server_info : 'unknown') . "\n";

$correo = 'prueba@example.com';
echo "Preparing select for correo={$correo}\n";
$stmt = $conn->prepare('SELECT id FROM users WHERE correo = ? LIMIT 1');
if (!$stmt) {
    echo "prepare failed: " . $conn->error . "\n";
    exit;
}
echo "prepare ok\n";

$ok = $stmt->bind_param('s', $correo);
echo "bind_param returned: " . ($ok ? 'true' : 'false') . "\n";

$ok = $stmt->execute();
echo "execute returned: " . ($ok ? 'true' : 'false') . "\n";
if (!$ok) { echo "execute error: " . $stmt->error . "\n"; }

$has_store = method_exists($stmt, 'store_result');
echo "has store_result(): " . ($has_store ? 'yes' : 'no') . "\n";
if ($has_store) { $stmt->store_result(); echo "called store_result\n"; }

$num = null;
if (isset($stmt->num_rows)) { $num = $stmt->num_rows; echo "stmt->num_rows: {$num}\n"; }

echo "Attempting to close stmt\n";
$stmt->close();

echo "Done.\n";

?>
