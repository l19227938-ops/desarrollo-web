document.addEventListener('DOMContentLoaded', () => {
  // Helper to post JSON and return parsed JSON
  async function postJson(url, obj) {
    const resp = await fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(obj)
    });
    const text = await resp.text();
    try { return JSON.parse(text); } catch (e) { return { success: false, error: text.slice(0, 500) }; }
  }

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = document.getElementById('msg');
      if (msg) msg.textContent = 'Enviando...';
      const correo = loginForm.querySelector('input[name="correo"]').value;
      const password = loginForm.querySelector('input[name="password"]').value;
      try {
        const data = await postJson('php/login.php', { correo, password });
        if (data && data.success) {
          // redirect to home (or a protected page)
          window.location.href = 'index.php';
        } else {
          if (msg) msg.textContent = data.error || 'Credenciales inválidas';
        }
      } catch (err) {
        if (msg) msg.textContent = 'Error de conexión';
        console.error('login post error', err);
      }
    });
  }

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const msg = document.getElementById('msg');
      if (msg) msg.textContent = 'Enviando...';
      const nombre = registerForm.querySelector('input[name="nombre"]').value;
      const correo = registerForm.querySelector('input[name="correo"]').value;
      const password = registerForm.querySelector('input[name="password"]').value;
      try {
        const data = await postJson('php/register.php', { nombre, correo, password });
        if (data && data.success) {
          // Optionally auto-login or redirect
          window.location.href = 'index.php';
        } else {
          if (msg) msg.textContent = data.error || 'Error al crear cuenta';
        }
      } catch (err) {
        if (msg) msg.textContent = 'Error de conexión';
        console.error('register post error', err);
      }
    });
  }
});
