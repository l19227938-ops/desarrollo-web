Proyecto Semestral — Etapa 3: Entrega Técnica

Resumen rápido
- Frameworks: Bootstrap 5 (frontend) y Vue.js (frontend dynamic list).
- Backend: PHP procedural con MySQL (conex.php). Endpoints principales: `php/login.php`, `php/register.php`, `php/list.php`, `php/insert.php`, `php/logout.php`, `php/session.php`.

Qué implementé para cumplir requisitos

1) Estructura y diseño
- HTML semántico: `header`, `main`, `section`, `footer` ya están presentes en `index.php`.
- CSS separado en `css/style.css`.
- Se usa Bootstrap grid (`container`, `row`, `col-...`) y componentes: Navbar, Carousel, Cards, Buttons, Modals.

2) Interactividad y lógica (JS)
- Todo JS está en archivos externos: `js/script.js` y nuevo `js/auth-pages.js`.
- Event listeners usan `addEventListener` (por ejemplo `submit`, `click`, `DOMContentLoaded`).
- Fetch/JSON:
  - `js/script.js` ya usaba `fetch()` para `php/insert.php` y `php/list.php`.
  - Añadí `js/auth-pages.js` para enviar `login` y `register` mediante `fetch()` enviando JSON y esperando JSON.
- Manejo de errores con `try/catch` y mensajes en la UI (`#msg`, `#loginMessage`, `#registerMessage`).

3) Integración de frameworks
- Bootstrap (obligatorio): usado a lo largo del sitio.
- Segundo framework elegido: Vue.js (ya presente) para render dinámico de lista de comentarios/usuarios en la home (`#vueApp`).

4) Manejo de sesiones y BD
- Endpoints `php/login.php` y `php/register.php` generan respuestas JSON.
- `php/session.php` (existente) se usa para chequear sesión desde `js/script.js`.
- Registro y login usan contraseñas con `password_hash`/`password_verify`.
- CRUD básico: `php/insert.php` y `php/list.php` manejan creación y lectura. (Si necesita Update/Delete puedo implementarlos para la entidad que prefieras — e.g., comentarios o productos).

5) Refactoring y mejoras realizadas
- Se estandarizó la comunicación cliente-servidor para JSON: añadí parsing de `application/json` en `php/login.php` y `php/register.php`.
- Todos los formularios de autenticación ahora usan un script externo `js/auth-pages.js` que envía JSON por `fetch()` y maneja la respuesta; esto reemplaza envíos por formulario puro y cumple el requisito de intercambio en JSON.
- Comentarios en código: los archivos `php/login.php` y `php/register.php` incluyen logging/`dbg()` y manejo de excepciones para retornar JSON en errores.
- Se reemplazó (en `js/script.js`) uso directo de XHR por `fetch` (ya estaba hecho), con manejo de `try/catch`.

Archivos clave modificados/añadidos
- Modificado: `index.php`, `js/script.js`, `php/login.php`, `php/register.php`.
- Añadido: `login.html`, `register.html`, `js/auth-pages.js`, `DELIVERY.md`.

Siguientes pasos recomendados
- Implementar Update/Delete para la entidad que vayan a exponer (usuarios, comentarios, productos).
- Añadir protecciones en el servidor para rutas que requieren sesión (ej: páginas de gestión de usuarios).
- Añadir validaciones más robustas en frontend (longitud de password, email) y en backend (sanitizar inputs, prepared statements ya usados).
- Pruebas: abrir sitio en Pillán y comprobar endpoints con `curl` o `fetch` (instrucciones abajo).

Cómo probar localmente (ejemplos)
- Desde el navegador, abre `index.php`, haz clic en "Login" o en "Register" — ahora van a `login.html` / `register.html`.
- En `login.html` y `register.html`, el envío usa `fetch()` y espera JSON; en caso de éxito redirige a `index.php`.

Ejemplos `curl` (para desarrolladores):

# Register (JSON)
```
curl -X POST -H "Content-Type: application/json" -d '{"nombre":"Test","correo":"test@example.com","password":"abc123"}' http://TU_HOST/Proyecto/Etapa3/php/register.php
```

# Login (JSON)
```
curl -X POST -H "Content-Type: application/json" -d '{"correo":"test@example.com","password":"abc123"}' -c cookies.txt http://TU_HOST/Proyecto/Etapa3/php/login.php
```

Si quieres, implemento:
- Auto-login al registrarse (crear sesión en `register.php`).
- Rutas protegidas y páginas de gestión (CRUD completo) para comentarios/usuarios.
- Tests automatizados o instrucciones para deploy en Pillán.


Integrantes: (Pon aquí los nombres del grupo antes de entregar)

