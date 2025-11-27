# Proyecto Semestral - Etapa 2

Breve guía para ejecutar y verificar los requisitos solicitados en la Etapa 2.

Requisitos implementados:
- HTML/CSS: `index.php` mantiene la maqueta semántica y enlaza `css/style.css`.
- JavaScript: `js/script.js` contiene al menos 3 funciones (ver comentarios), usa `this` en dos llamadas, maneja 5 eventos con addEventListener y usa console.log() en cada función.
- FETCH: Se usa para listar y traer un registro desde `php/list.php` (JSON).
- PHP/BD: `php/conex.php`, `php/insert.php`, `php/list.php`.

Archivos agregados/actualizados:
- `index.php` — Página principal (formulario y select para FETCH)
- `js/script.js` — Lógica JS: funciones, eventos, FETCH
- `php/conex.php` — Conexión PDO a MySQL
- `php/insert.php` — Inserta datos recibidos por POST (retorna JSON)
- `php/list.php` — Devuelve listados en JSON (opcional ?id=)
- `sql/schema.sql` — Script SQL para crear la base de datos y tabla

Instalación y configuración rápida (local, Windows):
1) Tener PHP y MySQL/MariaDB instalados (ej. XAMPP, Wampserver).
2) Crear la base de datos y tabla usando `sql/schema.sql` (ver más abajo).
3) Editar `php/conex.php` con las credenciales correctas (DB_HOST, DB_NAME, DB_USER, DB_PASS).
4) Copiar el proyecto a la carpeta pública de tu servidor (ej. htdocs para XAMPP).
5) Acceder en el navegador a `http://localhost/Etapa2/` (o ruta correspondiente).

Pruebas rápidas:
- En la página principal: llenar el formulario de contacto y presionar Enviar. Deberías ver en pantalla (DIV) un mensaje con el resultado y el registro quedará guardado en la BD.
- Usar el Select para elegir un registro existente y ver sus datos (traídos por FETCH en JSON).
- Presionar "Listar todos (JSON)" para ver el listado devuelto por `php/list.php`.

Notas de seguridad / producción:
- Este proyecto es educativo. Para producción se debe añadir validación adicional, protección CSRF, y no exponer errores SQL completos.

Cómo se cumplen los puntos JS (resumen):
- 3 funciones: `sumaYLog(a,b)`, `formateaContacto(ctx,nombre,correo)`, `mostrarResultado(ctx, contenedor, texto)`.
- Uso de `this` en `formateaContacto` y `mostrarResultado` (pasado desde handlers con `this`).
- 5 eventos: nav clicks, scroll, form submit, select change, list button click.
- console.log() incluido en cada función / handler.
