const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
  link.addEventListener('click', function onNavClick(e) {
    // Smooth scrolling to sections (keeps behavior from original implementation)
    e.preventDefault();
    const href = this.getAttribute('href');
    const target = document.querySelector(href);
    if (target) {
      window.scrollTo({ top: target.offsetTop - 60, behavior: 'smooth' });
    }
  });
});

window.addEventListener('scroll', function onScroll() {
  const sections = document.querySelectorAll('main section');
  let scrollPos = window.scrollY + 80;
  sections.forEach(section => {
    const id = section.getAttribute('id');
    const navItem = document.querySelector(`.nav-link[href="#${id}"]`);
    if (!navItem) return;
    if (section.offsetTop <= scrollPos && section.offsetTop + section.offsetHeight > scrollPos) {
      navItem.classList.add('active');
    } else {
      navItem.classList.remove('active');
    }
  });
});

/**
 * Utilidades y refactor notes
 * Se reemplazó uso directo de XMLHttpRequest por fetch/async-await para mayor claridad.
 * Se añadió manejo de errores con try/catch y mensajes claros al usuario.
 */
function sumaYLog(a, b) {
  const r = a + b;
  console.log('sumaYLog:', a, '+', b, '=', r);
  return r;
}

function formateaContacto(ctx, nombre, correo) {
  const out = `Nombre: ${nombre} | Correo: ${correo}`;
  console.log('formateaContacto:', out);
  return out;
}

function mostrarResultado(ctx, contenedor, texto) {
  contenedor.innerHTML = `<pre style="white-space:pre-wrap;">${texto}</pre>`;
  contenedor.classList.add('highlight');
  setTimeout(() => contenedor.classList.remove('highlight'), 2000);
}

const contactForm = document.getElementById('contactForm');
const personSelect = document.getElementById('personSelect');
const personResult = document.getElementById('personResult');
const listBtn = document.getElementById('listBtn');
const listResult = document.getElementById('listResult');

// Submit form using fetch and FormData with async/await
if (contactForm) {
  contactForm.addEventListener('submit', async function onSubmit(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formateaContacto(this, formData.get('nombre'), formData.get('correo'));
    try {
      const resp = await fetch('php/insert.php', { method: 'POST', body: formData });
      const data = await resp.json();
      if (data.success) {
        mostrarResultado(this, personResult, 'Registro guardado correctamente. ID: ' + data.insertId);
        await cargarOpciones();
      } else {
        mostrarResultado(this, personResult, 'Error: ' + (data.error || 'Respuesta inválida'));
      }
    } catch (err) {
      console.error('Fetch insert error:', err);
      mostrarResultado(this, personResult, 'Error de conexión al servidor.');
    }
  });
}

// When selecting a person, fetch single-item JSON
if (personSelect) {
  personSelect.addEventListener('change', async function onSelectChange(e) {
    if (!this.value) {
      personResult.innerHTML = '<em>Seleccione una opción válida.</em>';
      return;
    }
    try {
      const resp = await fetch(`php/list.php?id=${encodeURIComponent(this.value)}`);
      const data = await resp.json();
      if (data && data.length) {
        const p = data[0];
        mostrarResultado(this, personResult, `ID: ${p.id}\nNombre: ${p.nombre}\nCorreo: ${p.correo}\nTeléfono: ${p.telefono}\nMensaje: ${p.mensaje}`);
      } else {
        mostrarResultado(this, personResult, 'No se encontraron datos para el id seleccionado.');
      }
    } catch (err) {
      console.error('Fetch person error:', err);
      mostrarResultado(this, personResult, 'Error al traer datos.');
    }
  });
}

// List all
if (listBtn) {
  listBtn.addEventListener('click', async function onListClick(e) {
    try {
      const resp = await fetch('php/list.php');
      const data = await resp.json();
      if (!Array.isArray(data)) {
        listResult.innerText = 'Respuesta inesperada del servidor';
        return;
      }
      const html = data.map(p => `<div class="card mb-2 p-2"><strong>${p.nombre}</strong> — ${p.correo}<br><small>${p.telefono || ''}</small></div>`).join('');
      listResult.innerHTML = html;
    } catch (err) {
      console.error('Fetch list error:', err);
      listResult.innerText = 'Error al listar.';
    }
  });
}

// cargarOpciones ahora usa async/await
async function cargarOpciones() {
  if (!personSelect) return;
  try {
    const resp = await fetch('php/list.php');
    const data = await resp.json();
    personSelect.innerHTML = '<option value="">-- Seleccione nombre --</option>' + (Array.isArray(data) ? data.map(p => `<option value="${p.id}">${p.nombre} (${p.correo})</option>`).join('') : '');
  } catch (err) {
    console.error('Error cargando opciones:', err);
    personSelect.innerHTML = '<option value="">Error cargando opciones</option>';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  cargarOpciones();
});

// Vue 3 app: muestra dinámicamente los registros con acciones de edición/eliminación para usuarios logueados
if (window.Vue) {
  const { createApp } = Vue;
  createApp({
    data() {
      return {
        people: [],
        loading: false,
        user: window.currentUser || null,
        editing: null
      };
    },
    created() {
      // react to session changes
      window.addEventListener('session-changed', (e) => { this.user = e.detail; });
    },
    async mounted() {
      await this.load();
    },
    methods: {
      async load() {
        this.loading = true;
        try {
          const resp = await fetch('php/list.php');
          const data = await resp.json();
          this.people = Array.isArray(data) ? data : [];
        } catch (err) {
          console.error('Vue fetch error:', err);
          this.people = [];
        }
        this.loading = false;
      },
      canEdit() { return !!this.user && !!this.user.id; },
      edit(person) { this.editing = Object.assign({}, person); window.scrollTo({ top: 0, behavior: 'smooth' }); },
      async saveEdit() {
        const p = this.editing;
        try {
          const resp = await fetch('php/update_contact.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(p), credentials: 'same-origin' });
          const data = await resp.json();
          if (data.success) { this.editing = null; await this.load(); } else { alert(data.error || 'Error al guardar'); }
        } catch (e) { console.error(e); alert('Error de conexión'); }
      },
      cancelEdit() { this.editing = null; },
      async doDelete(id) {
        if (!confirm('¿Eliminar este registro?')) return;
        try {
          const resp = await fetch('php/delete_contact.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ id }), credentials: 'same-origin' });
          const data = await resp.json();
          if (data.success) { await this.load(); } else { alert(data.error || 'Error al eliminar'); }
        } catch (e) { console.error(e); alert('Error de conexión'); }
      }
    },
    template: `
      <div>
        <div v-if="editing" class="card mb-3 p-3">
          <h5>Editar registro</h5>
          <div class="mb-2"><input v-model="editing.nombre" class="form-control" placeholder="Nombre"></div>
          <div class="mb-2"><input v-model="editing.correo" class="form-control" placeholder="Correo"></div>
          <div class="mb-2"><input v-model="editing.telefono" class="form-control" placeholder="Teléfono"></div>
          <div class="mb-2"><textarea v-model="editing.mensaje" class="form-control" placeholder="Mensaje"></textarea></div>
          <button class="btn btn-sm btn-primary me-2" @click="saveEdit">Guardar</button>
          <button class="btn btn-sm btn-secondary" @click="cancelEdit">Cancelar</button>
        </div>
        <div v-if="loading">Cargando...</div>
        <div v-for="p in people" :key="p.id" class="card mb-2 p-2">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <strong>{{ p.nombre }}</strong> — {{ p.correo }}<br>
              <small>{{ p.telefono || '' }}</small>
              <p class="mb-0">{{ p.mensaje }}</p>
            </div>
            <div v-if="canEdit()" class="text-end">
              <button class="btn btn-sm btn-outline-secondary me-1" @click="edit(p)">Editar</button>
              <button class="btn btn-sm btn-outline-danger" @click="doDelete(p.id)">Eliminar</button>
            </div>
          </div>
        </div>
      </div>`
  }).mount('#vueApp');
}

window.appUtils = { sumaYLog, formateaContacto, mostrarResultado };

// --- Authentication UI and handlers ---
async function checkSession() {
  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 10000);
    const resp = await fetch('php/session.php', { credentials: 'same-origin', signal: controller.signal });
    clearTimeout(timeout);
    const data = await resp.json();
    // expose current user globally for other scripts (e.g., Vue app) to use
    window.currentUser = data.user || null;
    // notify any listeners that session changed
    try { window.dispatchEvent(new CustomEvent('session-changed', { detail: window.currentUser })); } catch (e) { /* ignore */ }
    renderAuthArea(window.currentUser);
  } catch (err) {
    console.error('Session check error:', err);
    renderAuthArea(null);
  }
}

function renderAuthArea(user) {
  const authArea = document.getElementById('authArea');
  if (!authArea) return;
  if (user && user.id) {
    authArea.innerHTML = `
      <div class="d-flex align-items-center">
        <span class="me-3">Hola, <strong>${escapeHtml(user.nombre)}</strong></span>
        <button id="btnLogout" class="btn btn-outline-secondary btn-sm">Logout</button>
      </div>`;
    const btnLogout = document.getElementById('btnLogout');
    if (btnLogout) btnLogout.addEventListener('click', handleLogout);
  } else {
    authArea.innerHTML = `
      <a id="btnLogin" class="btn btn-outline-primary btn-sm me-2" href="login.html">Login</a>
      <a id="btnRegister" class="btn btn-primary btn-sm" href="register.html">Register</a>`;
    // No need to attach listeners for opening modals (Bootstrap handles data-bs-toggle)
  }
}

function escapeHtml(str) {
  return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// Login handler
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(loginForm);
    const msg = document.getElementById('loginMessage');
    msg.textContent = 'Enviando...';
    const submitBtn = loginForm.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;
    try {
          const controller = new AbortController();
          const timeout = setTimeout(() => controller.abort(), 10000);
          const resp = await fetch('php/login.php', { method: 'POST', body: formData, credentials: 'same-origin', signal: controller.signal });
          clearTimeout(timeout);
        let data;
        const contentType = resp.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
          data = await resp.json();
        } else {
          // fallback: server returned HTML/text (likely an error), read and show
          const text = await resp.text();
          console.error('Login non-JSON response:', text);
          msg.textContent = text.slice(0, 300) || 'Respuesta inesperada del servidor';
          return;
        }
        if (data.success) {
          // close modal if bootstrap available, otherwise reload as fallback
          const modalEl = document.getElementById('loginModal');
          try {
            if (window.bootstrap && bootstrap.Modal) {
              const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
              modal.hide();
            } else {
              // fallback: reload to refresh auth area
              location.reload();
              return;
            }
          } catch (err) {
            console.warn('Could not hide bootstrap modal, reloading', err);
            location.reload();
            return;
          }
          await checkSession();
        } else {
          msg.textContent = data.error || 'Credenciales inválidas';
        }
    } catch (err) {
      console.error('Login error:', err);
      msg.textContent = 'Error de conexión';
    }
    if (submitBtn) submitBtn.disabled = false;
  });
}

// Register handler
const registerForm = document.getElementById('registerForm');
if (registerForm) {
  registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(registerForm);
    const msg = document.getElementById('registerMessage');
    msg.textContent = 'Enviando...';
    const submitBtn = registerForm.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;
    try {
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 10000);
      const resp = await fetch('php/register.php', { method: 'POST', body: formData, credentials: 'same-origin', signal: controller.signal });
      clearTimeout(timeout);
      let data;
      const contentType = resp.headers.get('content-type') || '';
      if (contentType.includes('application/json')) {
        data = await resp.json();
      } else {
        const text = await resp.text();
        console.error('Register non-JSON response:', text);
        msg.textContent = text.slice(0, 300) || 'Respuesta inesperada del servidor';
        return;
      }
      if (data.success) {
        const modalEl = document.getElementById('registerModal');
        try {
          if (window.bootstrap && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
          } else {
            location.reload();
            return;
          }
        } catch (err) {
          console.warn('Could not hide bootstrap modal, reloading', err);
          location.reload();
          return;
        }
        // Optionally log in the user automatically? For now, just refresh session state
        await checkSession();
      } else {
        msg.textContent = data.error || 'Error al crear cuenta';
      }
    } catch (err) {
      console.error('Register error:', err);
      msg.textContent = 'Error de conexión';
    }
    if (submitBtn) submitBtn.disabled = false;
  });
}

// Logout
async function handleLogout(e) {
  try {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 10000);
    const resp = await fetch('php/logout.php', { credentials: 'same-origin', signal: controller.signal });
    clearTimeout(timeout);
    const data = await resp.json();
    if (data.success) {
      await checkSession();
    }
  } catch (err) {
    console.error('Logout error:', err);
  }
}

// Run session check on load
document.addEventListener('DOMContentLoaded', () => {
  checkSession();
});
