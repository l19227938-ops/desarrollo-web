<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Café Aroma - Venta de Café</title>
  <meta name="description" content="Café Aroma: Venta de café premium, servicios, galería y contacto. Sitio web one page validado W3C.">
  <link rel="stylesheet" href="css/style.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!--
    Estructura semántica principal del sitio:
    - <header>: Barra de navegación principal
    - <main>: Contenido principal dividido en secciones
    - <section>: Cada área temática (inicio, productos, galería, contacto)
    - <footer>: Información legal y validación W3C
  -->
  <header class="header">
    <!-- Bootstrap responsive navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="#inicio">Café Aroma</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="#galeria">Galería</a></li>
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
          </ul>
          <div id="authArea" class="ms-3 d-flex align-items-center">
            <!-- Auth links (se rellenan por JS): anchor href allow direct navigation when JS is disabled -->
            <a id="btnLogin" class="btn btn-outline-primary btn-sm me-2" href="login.html">Login</a>
            <a id="btnRegister" class="btn btn-primary btn-sm" href="register.html">Register</a>
          </div>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <section id="inicio" class="hero">
      <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="imagenes/cafe_artesanal.jpg" class="d-block w-100" alt="Taza de café artesanal">
            <div class="carousel-caption d-none d-md-block text-start">
              <h1 class="display-5">Café Aroma</h1>
              <p>Disfruta el mejor café premium, directo desde los productores a tu taza.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="imagenes/granos_de_cafe.jpg" class="d-block w-100" alt="Granos de café premium">
            <div class="carousel-caption d-none d-md-block text-start">
              <h2>Nuestros granos</h2>
              <p>Selección y tueste artesanal para una taza superior.</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="imagenes/accesorios_cafe.jpg" class="d-block w-100" alt="Accesorios para café">
            <div class="carousel-caption d-none d-md-block text-start">
              <h2>Accesorios</h2>
              <p>Todo lo que necesitas para tu experiencia cafetera.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>
    <section id="productos" class="services py-5 bg-white">
      <div class="container">
        <h2 class="services__title">Nuestros Productos</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card h-100">
              <img src="imagenes/granos_de_cafe.jpg" class="card-img-top" alt="Café en grano">
              <div class="card-body">
                <h5 class="card-title">Café en Grano</h5>
                <p class="card-text">Granos seleccionados, tostado artesanal. Presentaciones: 250g, 500g, 1kg.</p>
              </div>
              <div class="card-footer bg-transparent border-top-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">Ver</button>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100">
              <img src="imagenes/cafe_artesanal.jpg" class="card-img-top" alt="Café molido">
              <div class="card-body">
                <h5 class="card-title">Café Molido</h5>
                <p class="card-text">Listo para tu cafetera o prensa francesa. Frescura garantizada.</p>
              </div>
              <div class="card-footer bg-transparent border-top-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">Ver</button>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100">
              <img src="imagenes/accesorios_cafe.jpg" class="card-img-top" alt="Accesorios">
              <div class="card-body">
                <h5 class="card-title">Accesorios</h5>
                <p class="card-text">Filtros, tazas, y más para tu experiencia cafetera.</p>
              </div>
              <div class="card-footer bg-transparent border-top-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">Ver</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Vue demo component: lista dinámica (se conecta a php/list.php) -->
        <hr class="my-5">
        <div id="vueApp">
          <h3>Comentarios / Contactos (dinámico con Vue)</h3>
          <div class="row">
            <div class="col-md-6" v-for="p in people" :key="p.id">
              <div class="card mb-3">
                <div class="card-body">
                  <h5 class="card-title">{{ p.nombre }}</h5>
                  <p class="card-text">{{ p.mensaje }}</p>
                  <p class="card-text"><small class="text-muted">{{ p.correo }} — {{ p.telefono }}</small></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="galeria" class="gallery">
      <h2 class="gallery__title">Galería</h2>
      <div class="gallery__grid">
        <!-- Imágenes de la galería, ahora en la carpeta 'imagenes/' para cumplir la pauta -->
        <img src="imagenes/cafe_artesanal.jpg" alt="Taza de café artesanal" class="gallery__img">
        <img src="imagenes/granos_de_cafe.jpg" alt="Granos de café premium" class="gallery__img">
        <img src="imagenes/accesorios_cafe.jpg" alt="Accesorios para café" class="gallery__img">
      </div>
    </section>
    <section id="contacto" class="contact">
      <h2 class="contact__title">Contacto y Pedidos</h2>
      <form id="contactForm" class="contact__form" action="php/insert.php" method="post">
    <!-- Formulario de contacto: los datos se envían a insert.php para ser guardados en la BD -->
        <label for="nombre" class="contact__label">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre" class="contact__input">
        <label for="correo" class="contact__label">Correo:</label>
        <input type="email" id="correo" name="correo" required placeholder="tu@email.com" class="contact__input">
        <label for="telefono" class="contact__label">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" placeholder="+56 9 1234 5678" class="contact__input">
        <label for="mensaje" class="contact__label">Mensaje o pedido:</label>
        <textarea id="mensaje" name="mensaje" required placeholder="¿Qué café te interesa?" class="contact__input"></textarea>
        <button type="submit" class="contact__btn">Enviar</button>
      </form>

      <hr style="margin:2rem 0;">
      <div style="max-width:700px; margin:0 auto;">
        <h3>Buscar contacto (trae datos desde la base de datos usando FETCH)</h3>
        <label for="personSelect">Selecciona un nombre:</label>
        <select id="personSelect" style="width:100%; padding:0.6rem; margin:0.5rem 0;">
          <option value="">-- Cargando opciones --</option>
        </select>
        <div id="personResult" style="margin-top:1rem; background:#fff; padding:1rem; border-radius:0.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
          <em>Los datos seleccionados aparecerán aquí.</em>
        </div>
        <button id="listBtn" style="margin-top:1rem;" class="contact__btn">Listar todos (JSON)</button>
        <div id="listResult" style="margin-top:1rem;"></div>
      </div>
    </section>
  </main>
  <footer class="footer">
    <aside class="footer__badges">
        <a href="#" target="_blank" rel="noopener" title="Validación HTML W3C">
          <img src="https://www.w3.org/html/logo/badge/html5-badge-h-solo.png" alt="HTML5 W3C Validado" style="height:40px; margin-right:8px;">
        </a>
    </aside>
  <p class="footer__w3c">Este sitio cumple con estándares W3C (HTML/CSS).</p>
  <p class="footer__desc" style="margin-top:1rem; font-size:0.95rem; color:#ccc;">Café Aroma &copy; 2025. Venta de café premium y accesorios.</p>
  </footer>
  <!-- Bootstrap JS bundle (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Vue.js (CDN) -->
  <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

  <!-- Product modal used by cards -->
  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="productModalLabel">Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Selecciona un producto para ver detalles.</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  
      <!-- Login Modal -->
      <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="loginForm" action="php/login.php" method="post">
                <div class="mb-3">
                  <label for="loginCorreo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="loginCorreo" name="correo" required>
                </div>
                <div class="mb-3">
                  <label for="loginPassword" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="loginPassword" name="password" required>
                </div>
                <div id="loginMessage" class="mb-2 text-danger" role="status" aria-live="polite"></div>
                <button type="submit" class="btn btn-primary">Entrar</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Register Modal -->
      <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="registerModalLabel">Registro</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="registerForm" action="php/register.php" method="post">
                <div class="mb-3">
                  <label for="regNombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="regNombre" name="nombre" required>
                </div>
                <div class="mb-3">
                  <label for="regCorreo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="regCorreo" name="correo" required>
                </div>
                <div class="mb-3">
                  <label for="regPassword" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="regPassword" name="password" required>
                </div>
                <div id="registerMessage" class="mb-2 text-danger" role="status" aria-live="polite"></div>
                <button type="submit" class="btn btn-primary">Crear cuenta</button>
              </form>
            </div>
          </div>
        </div>
        <script src="js/script.js"></script>
        <noscript>
          <div class="container mt-3">
            <div class="alert alert-warning">JavaScript está deshabilitado o no cargó correctamente. Para una mejor experiencia habilita JavaScript. Los formularios seguirán funcionando, pero la navegación puede mostrar respuestas JSON.</div>
          </div>
        </noscript>
      </div>
  </div>
</body>
</html>
