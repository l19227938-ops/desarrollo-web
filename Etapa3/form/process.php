<?php
// =============================
// Capturando datos del formulario
// =============================
$nombre = isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '';
$correo = isset($_GET['correo']) ? htmlspecialchars($_GET['correo']) : '';
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';

// =============================
// Mostrando resultados en pantalla
// =============================
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación de Contacto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <main style="max-width: 600px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">
    <h1>Los datos fueron recibidos con éxito</h1>
    <table style="width:100%; border-collapse: collapse; margin-top:2rem;">
      <tr><th style="text-align:left; padding:0.5rem;">Nombre</th><td style="padding:0.5rem;"><?php echo $nombre; ?></td></tr>
      <tr><th style="text-align:left; padding:0.5rem;">Correo</th><td style="padding:0.5rem;"><?php echo $correo; ?></td></tr>
      <tr><th style="text-align:left; padding:0.5rem;">Mensaje</th><td style="padding:0.5rem;"><?php echo $mensaje; ?></td></tr>
    </table>
    <a href="../index.html" style="display:inline-block; margin-top:2rem; color:#0077cc; text-decoration:underline;">Volver al sitio</a>
  </main>
</body>
</html>
