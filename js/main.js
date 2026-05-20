// ============================================================
// main.js — Portafolio Estudiantil
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // ----------------------------------------------------------
    // 1. Navbar: resaltar link activo al hacer scroll
    // ----------------------------------------------------------
    const navbar  = document.getElementById('navbar');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        // Clases al hacer scroll
        navbar.classList.toggle('scrolled', window.scrollY > 50);

        // Link activo
        let current = '';
        sections.forEach(sec => {
            if (window.scrollY >= sec.offsetTop - 100) current = sec.id;
        });
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) link.classList.add('active');
        });
    });

    // ----------------------------------------------------------
    // 2. Animación fade-up con IntersectionObserver
    // ----------------------------------------------------------
    document.querySelectorAll('.skill-card, .tech-item, .proyecto-card').forEach(el => {
        el.classList.add('fade-up');
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 60);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

    // ----------------------------------------------------------
    // 3. Animación de barras de progreso
    // ----------------------------------------------------------
    const barObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target.querySelector('.progress-bar');
                if (bar) {
                    const w = bar.dataset.width || 0;
                    setTimeout(() => bar.style.width = w + '%', 200);
                }
                barObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    document.querySelectorAll('.tech-progress').forEach(el => barObserver.observe(el));

    // ----------------------------------------------------------
    // 4. Formulario de Contacto — AJAX
    // ----------------------------------------------------------
    const formContacto = document.getElementById('formContacto');
    const msgExito     = document.getElementById('msg-exito');
    const msgError     = document.getElementById('msg-error');
    const btnEnviar    = document.getElementById('btnEnviar');

    if (formContacto) {
        formContacto.addEventListener('submit', async (e) => {
            e.preventDefault();
            msgExito.classList.add('d-none');
            msgError.classList.add('d-none');
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

            const datos = new FormData(formContacto);

            try {
                const resp = await fetch('php/contacto.php', { method: 'POST', body: datos });
                const json = await resp.json();

                if (json.ok) {
                    msgExito.classList.remove('d-none');
                    formContacto.reset();
                } else {
                    msgError.textContent = json.error || 'Error al enviar el mensaje.';
                    msgError.classList.remove('d-none');
                }
            } catch (err) {
                msgError.textContent = 'Error de conexión. Intenta de nuevo.';
                msgError.classList.remove('d-none');
            } finally {
                btnEnviar.disabled = false;
                btnEnviar.innerHTML = '<i class="bi bi-send-fill me-2"></i>Enviar Mensaje';
            }
        });
    }

    // ----------------------------------------------------------
    // 5. Formulario de Login — AJAX
    // ----------------------------------------------------------
    const formLogin  = document.getElementById('formLogin');
    const loginError = document.getElementById('login-error');

    if (formLogin) {
        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault();
            loginError.classList.add('d-none');
            const btn = formLogin.querySelector('button[type="submit"]');
            btn.disabled = true;

            const datos = new FormData(formLogin);

            try {
                const resp = await fetch('php/login.php', { method: 'POST', body: datos });
                const json = await resp.json();

                if (json.ok) {
                    window.location.reload();
                } else {
                    loginError.textContent = json.error || 'Credenciales incorrectas.';
                    loginError.classList.remove('d-none');
                }
            } catch (err) {
                loginError.textContent = 'Error de conexión.';
                loginError.classList.remove('d-none');
            } finally {
                btn.disabled = false;
            }
        });
    }

});
