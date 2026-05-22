// ============================================================
// dashboard.js — Lógica del Panel Administrativo
// Maneja navegación entre secciones y CRUD vía AJAX
// ============================================================

// ---- Referencias a los modales de Bootstrap ----
// Se inicializan al cargar para poder abrirlos/cerrarlos desde JS
let modalHabilidad, modalTecnologia, modalProyecto, modalEliminar;

// ---- Variable para la acción de eliminación pendiente ----
// Guarda la función a ejecutar cuando el usuario confirma eliminar
let accionEliminar = null;

// ============================================================
// INICIALIZACIÓN AL CARGAR EL DOM
// ============================================================
document.addEventListener('DOMContentLoaded', () => {

    // Inicializar instancias de modales Bootstrap
    modalHabilidad  = new bootstrap.Modal(document.getElementById('modalHabilidad'));
    modalTecnologia = new bootstrap.Modal(document.getElementById('modalTecnologia'));
    modalProyecto   = new bootstrap.Modal(document.getElementById('modalProyecto'));
    modalEliminar   = new bootstrap.Modal(document.getElementById('modalEliminar'));

    // ---- Navegación del sidebar ----
    // Cada link con data-seccion cambia la sección visible
    document.querySelectorAll('[data-seccion]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const seccion = link.dataset.seccion;
            cambiarSeccion(seccion);
        });
    });

    // ---- Botón confirmar eliminación ----
    document.getElementById('btnConfirmarEliminar').addEventListener('click', () => {
        if (typeof accionEliminar === 'function') {
            accionEliminar();       // Ejecuta la acción guardada
            accionEliminar = null;  // Limpia para el próximo uso
        }
        modalEliminar.hide();
    });

    // ---- Sidebar mobile: abrir y cerrar ----
    const sidebar        = document.getElementById('sidebar');
    const btnAbrir       = document.getElementById('btnAbrirSidebar');
    const btnCerrar      = document.getElementById('btnCerrarSidebar');

    if (btnAbrir)  btnAbrir.addEventListener('click',  () => sidebar.classList.add('abierto'));
    if (btnCerrar) btnCerrar.addEventListener('click', () => sidebar.classList.remove('abierto'));

    // Cerrar sidebar al hacer click fuera (en mobile)
    document.getElementById('dashboardMain').addEventListener('click', () => {
        sidebar.classList.remove('abierto');
    });

    // Cargar la sección de inicio por defecto
    cambiarSeccion('inicio');
});

// ============================================================
// FUNCIÓN: cambiarSeccion
// Muestra la sección indicada y oculta las demás
// También carga los datos si la sección los necesita
// ============================================================
function cambiarSeccion(nombre) {

    // Ocultar todas las secciones
    document.querySelectorAll('.dash-seccion').forEach(s => s.classList.remove('activa'));

    // Mostrar la sección indicada
    const seccion = document.getElementById('seccion-' + nombre);
    if (seccion) seccion.classList.add('activa');

    // Actualizar links activos en el sidebar
    document.querySelectorAll('[data-seccion]').forEach(l => l.classList.remove('active'));
    document.querySelectorAll(`[data-seccion="${nombre}"]`).forEach(l => l.classList.add('active'));

    // Actualizar título del topbar
    const titulos = {
        inicio:      'Inicio',
        biografia:   'Editar Biografía',
        habilidades: 'Habilidades y Herramientas',
        tecnologias: 'Tecnologías Dominadas',
        proyectos:   'Proyectos Realizados',
        mensajes:    'Mensajes Recibidos',
    };
    document.getElementById('pageTitle').textContent = titulos[nombre] || nombre;

    // Cargar datos según la sección
    if (nombre === 'biografia')   cargarFormBiografia();
    if (nombre === 'habilidades') cargarTablaHabilidades();
    if (nombre === 'tecnologias') cargarTablaTecnologias();
    if (nombre === 'proyectos')   cargarTablaProyectos();
    if (nombre === 'mensajes')    cargarTablaMensajes();
}

// ============================================================
// HELPERS GENERALES
// ============================================================

/**
 * Muestra una alerta temporal dentro de un contenedor
 * @param {string} contenedorId - ID del elemento donde mostrar el mensaje
 * @param {string} tipo         - 'success' | 'danger' | 'warning'
 * @param {string} texto        - Texto del mensaje
 */
function mostrarFeedback(contenedorId, tipo, texto) {
    const el = document.getElementById(contenedorId);
    if (!el) return;
    el.className = `alert alert-${tipo}`;
    el.textContent = texto;
    el.classList.remove('d-none');
    // Auto-ocultar después de 4 segundos
    setTimeout(() => el.classList.add('d-none'), 4000);
}

/**
 * Muestra el modal de confirmación de eliminación
 * @param {Function} callback - Función a ejecutar si el usuario confirma
 */
function confirmarEliminar(callback) {
    accionEliminar = callback;
    modalEliminar.show();
}

/**
 * Realiza una petición AJAX (fetch) a un endpoint PHP
 * @param {string} url    - Ruta del endpoint
 * @param {FormData|null} datos - Datos a enviar (POST) o null (GET)
 * @returns {Promise<Object>} - JSON de respuesta
 */
async function ajax(url, datos = null) {
    const opciones = datos
        ? { method: 'POST', body: datos }
        : { method: 'GET' };
    const resp = await fetch(url, opciones);
    return await resp.json();
}

// ============================================================
// SECCIÓN: BIOGRAFÍA
// ============================================================

/**
 * Carga el formulario de edición de biografía vía AJAX
 * Obtiene los datos actuales y rellena el formulario
 */
async function cargarFormBiografia() {
    const contenedor = document.getElementById('bio-form-container');

    try {
        // Obtener datos actuales de la BD
        const data = await ajax('../php/api/biografia.php');

        const b = data.biografia || {};

        // Renderizar formulario con los datos actuales
        contenedor.innerHTML = `
            <div class="bio-grid">
                <!-- Nombre completo -->
                <div>
                    <label class="form-label text-white">Nombre Completo</label>
                    <input type="text" id="bio-nombre" class="form-input" value="${esc(b.nombre)}">
                </div>
                <!-- Título / cargo -->
                <div>
                    <label class="form-label text-white">Título / Cargo</label>
                    <input type="text" id="bio-titulo" class="form-input" value="${esc(b.titulo)}">
                </div>
                <!-- Ubicación -->
                <div>
                    <label class="form-label text-white">Ubicación</label>
                    <input type="text" id="bio-ubicacion" class="form-input" value="${esc(b.ubicacion)}">
                </div>
                <!-- Email -->
                <div>
                    <label class="form-label text-white">Email</label>
                    <input type="email" id="bio-email" class="form-input" value="${esc(b.email)}">
                </div>
                <!-- Teléfono -->
                <div>
                    <label class="form-label text-white">Teléfono</label>
                    <input type="text" id="bio-telefono" class="form-input" value="${esc(b.telefono)}">
                </div>
                <!-- GitHub -->
                <div>
                    <label class="form-label text-white">GitHub URL</label>
                    <input type="url" id="bio-github" class="form-input" value="${esc(b.github_url)}">
                </div>
                <!-- LinkedIn -->
                <div>
                    <label class="form-label text-white">LinkedIn URL</label>
                    <input type="url" id="bio-linkedin" class="form-input" value="${esc(b.linkedin_url)}">
                </div>
                <!-- Twitter -->
                <div>
                    <label class="form-label text-white">Twitter/X URL</label>
                    <input type="url" id="bio-twitter" class="form-input" value="${esc(b.twitter_url)}">
                </div>
                <!-- Descripción principal (ocupa toda la fila) -->
                <div class="col-full">
                    <label class="form-label text-white">Descripción Principal</label>
                    <textarea id="bio-descripcion" class="form-input" rows="4">${esc(b.descripcion)}</textarea>
                </div>
                <!-- Segunda descripción -->
                <div class="col-full">
                    <label class="form-label text-white">Segunda Descripción</label>
                    <textarea id="bio-descripcion2" class="form-input" rows="4">${esc(b.descripcion2)}</textarea>
                </div>
                <!-- Botón guardar -->
                <div class="col-full">
                    <button class="btn-dash-primary" onclick="guardarBiografia()">
                        <i class="bi bi-save me-2"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        `;
    } catch (e) {
        contenedor.innerHTML = '<p class="text-danger">Error al cargar los datos.</p>';
    }
}

/**
 * Guarda los cambios de la biografía vía AJAX
 */
async function guardarBiografia() {
    const datos = new FormData();
    datos.append('accion',       'actualizar');
    datos.append('nombre',       document.getElementById('bio-nombre').value);
    datos.append('titulo',       document.getElementById('bio-titulo').value);
    datos.append('ubicacion',    document.getElementById('bio-ubicacion').value);
    datos.append('email',        document.getElementById('bio-email').value);
    datos.append('telefono',     document.getElementById('bio-telefono').value);
    datos.append('github_url',   document.getElementById('bio-github').value);
    datos.append('linkedin_url', document.getElementById('bio-linkedin').value);
    datos.append('twitter_url',  document.getElementById('bio-twitter').value);
    datos.append('descripcion',  document.getElementById('bio-descripcion').value);
    datos.append('descripcion2', document.getElementById('bio-descripcion2').value);

    try {
        const res = await ajax('../php/api/biografia.php', datos);
        mostrarFeedback('bio-feedback', res.ok ? 'success' : 'danger',
            res.ok ? '✓ Biografía actualizada correctamente.' : res.error);
    } catch (e) {
        mostrarFeedback('bio-feedback', 'danger', 'Error de conexión.');
    }
}

// ============================================================
// SECCIÓN: HABILIDADES — CRUD
// ============================================================

/**
 * Rellena un <select> de orden con las posiciones disponibles.
 * Carga todos los registros del endpoint dado y construye opciones
 * del 1 al total+1, excluyendo las posiciones ya ocupadas por OTROS
 * registros (el propio registro —identificado por excluirId— puede
 * conservar su posición actual).
 *
 * @param {string} selectId     - ID del elemento <select> en el DOM
 * @param {string} endpoint     - URL de la API (p. ej. '../php/api/habilidades.php')
 * @param {string} clave        - Clave del array en la respuesta JSON (p. ej. 'habilidades')
 * @param {number|null} excluirId   - ID del registro que se está editando (null = nuevo)
 * @param {number} ordenActual  - Valor de orden que debe quedar seleccionado
 */
async function cargarSelectOrden(selectId, endpoint, clave, excluirId, ordenActual) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option disabled>Cargando…</option>';

    try {
        const data  = await ajax(endpoint);
        const items = data[clave] || [];

        // Posiciones ya ocupadas por OTROS registros
        const ocupadas = new Set(
            items
                .filter(item => item.id !== excluirId)   // excluir el propio registro
                .map(item => Number(item.orden))
        );

        // Rango: del 1 al total de registros + 1 (siempre hay sitio al final)
        const max = items.length + 1;

        select.innerHTML = '';
        for (let i = 1; i <= max; i++) {
            if (ocupadas.has(i)) continue;               // saltar posiciones tomadas
            const opt = document.createElement('option');
            opt.value       = i;
            opt.textContent = `Posición ${i}`;
            if (i === Number(ordenActual)) opt.selected = true;
            select.appendChild(opt);
        }

        // Si el ordenActual no aparece en las opciones (p. ej. era 0 o ya no existe),
        // seleccionar la primera opción disponible
        if (!select.value || select.value === '0') {
            select.selectedIndex = 0;
        }

    } catch (e) {
        select.innerHTML = '<option value="1">Posición 1</option>';
    }
}



/** Carga y renderiza la tabla de habilidades */
async function cargarTablaHabilidades() {
    const contenedor = document.getElementById('tabla-habilidades');
    try {
        const data = await ajax('../php/api/habilidades.php');
        const items = data.habilidades || [];

        if (items.length === 0) {
            contenedor.innerHTML = '<p class="text-muted text-center py-3">No hay habilidades registradas.</p>';
            return;
        }

        // Construir tabla HTML
        let html = `
            <div class="table-responsive">
            <table class="tabla-dash">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Ícono</th>
                        <th class="col-ocultar-mobile">Colores</th>
                        <th class="col-ocultar-mobile">Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;

        items.forEach(h => {
            html += `
                <tr>
                    <td class="text-muted">${h.id}</td>
                    <td><strong>${esc(h.nombre)}</strong></td>
                    <td>
                        <!-- Vista previa del ícono con su gradiente -->
                        <div style="
                            width:32px;height:32px;border-radius:8px;
                            background:linear-gradient(135deg,${h.color_desde},${h.color_hasta});
                            display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;">
                            <i class="${esc(h.icono)}"></i>
                        </div>
                    </td>
                    <td class="col-ocultar-mobile">
                        <span style="color:${h.color_desde}">●</span>
                        <span style="color:${h.color_hasta}">●</span>
                    </td>
                    <td class="col-ocultar-mobile">${h.orden}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-editar"
                                onclick="abrirModalHabilidad(${h.id},'${esc(h.nombre)}','${esc(h.icono)}','${h.color_desde}','${h.color_hasta}',${h.orden})">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn-eliminar" onclick="eliminarHabilidad(${h.id})">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        contenedor.innerHTML = html;

    } catch (e) {
        contenedor.innerHTML = '<p class="text-danger">Error al cargar habilidades.</p>';
    }
}

/**
 * Abre el modal de habilidad en modo crear o editar
 * Si se pasa id, rellena los campos para edición
 */
async function abrirModalHabilidad(id='', nombre='', icono='bi bi-code-slash', colorDesde='#3b82f6', colorHasta='#8b5cf6', orden=0) {
    document.getElementById('hab-id').value          = id;
    document.getElementById('hab-nombre').value      = nombre;
    document.getElementById('hab-icono').value       = icono;
    document.getElementById('hab-color-desde').value = colorDesde;
    document.getElementById('hab-color-hasta').value = colorHasta;

    // Cambiar título según si es crear o editar
    document.getElementById('tituloModalHabilidad').textContent = id ? 'Editar Habilidad' : 'Nueva Habilidad';

    // Cargar select de orden con posiciones disponibles
    await cargarSelectOrden('hab-orden', '../php/api/habilidades.php', 'habilidades', id ? Number(id) : null, orden);

    modalHabilidad.show();
}

/** Guarda (crea o actualiza) una habilidad */
async function guardarHabilidad() {
    const datos = new FormData();
    datos.append('accion',      document.getElementById('hab-id').value ? 'actualizar' : 'crear');
    datos.append('id',          document.getElementById('hab-id').value);
    datos.append('nombre',      document.getElementById('hab-nombre').value);
    datos.append('icono',       document.getElementById('hab-icono').value);
    datos.append('color_desde', document.getElementById('hab-color-desde').value);
    datos.append('color_hasta', document.getElementById('hab-color-hasta').value);
    datos.append('orden',       document.getElementById('hab-orden').value || 0);

    try {
        const res = await ajax('../php/api/habilidades.php', datos);
        modalHabilidad.hide();
        if (res.ok) {
            cargarTablaHabilidades(); // Recargar tabla
        } else {
            alert('Error: ' + (res.error || 'No se pudo guardar.'));
        }
    } catch (e) {
        alert('Error de conexión.');
    }
}

/** Elimina una habilidad previa confirmación */
function eliminarHabilidad(id) {
    confirmarEliminar(async () => {
        const datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('id', id);
        const res = await ajax('../php/api/habilidades.php', datos);
        if (res.ok) cargarTablaHabilidades();
        else alert('Error al eliminar.');
    });
}

// ============================================================
// SECCIÓN: TECNOLOGÍAS — CRUD
// ============================================================

/** Carga y renderiza la tabla de tecnologías */
async function cargarTablaTecnologias() {
    const contenedor = document.getElementById('tabla-tecnologias');
    try {
        const data  = await ajax('../php/api/tecnologias.php');
        const items = data.tecnologias || [];

        if (items.length === 0) {
            contenedor.innerHTML = '<p class="text-muted text-center py-3">No hay tecnologías registradas.</p>';
            return;
        }

        let html = `
            <div class="table-responsive">
            <table class="tabla-dash">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Nivel</th>
                        <th class="col-ocultar-mobile">Categoría</th>
                        <th class="col-ocultar-mobile">Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;

        items.forEach(t => {
            html += `
                <tr>
                    <td><strong>${esc(t.nombre)}</strong></td>
                    <td>
                        <!-- Mini barra de progreso en la tabla -->
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:80px;height:6px;background:rgba(255,255,255,0.1);border-radius:999px;overflow:hidden;">
                                <div style="width:${t.nivel}%;height:100%;background:linear-gradient(90deg,${t.color_desde},${t.color_hasta});border-radius:999px;"></div>
                            </div>
                            <span style="color:${t.color_desde};font-weight:700;font-size:0.85rem">${t.nivel}%</span>
                        </div>
                    </td>
                    <td class="col-ocultar-mobile">
                        <span class="cat-badge">${esc(t.categoria)}</span>
                    </td>
                    <td class="col-ocultar-mobile">${t.orden}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-editar"
                                onclick="abrirModalTecnologia(${t.id},'${esc(t.nombre)}',${t.nivel},'${esc(t.categoria)}','${t.color_desde}','${t.color_hasta}',${t.orden})">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn-eliminar" onclick="eliminarTecnologia(${t.id})">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        contenedor.innerHTML = html;

    } catch (e) {
        contenedor.innerHTML = '<p class="text-danger">Error al cargar tecnologías.</p>';
    }
}

/** Abre el modal de tecnología en modo crear o editar */
async function abrirModalTecnologia(id='', nombre='', nivel=75, categoria='Frontend', colorDesde='#3b82f6', colorHasta='#06b6d4', orden=0) {
    document.getElementById('tec-id').value          = id;
    document.getElementById('tec-nombre').value      = nombre;
    document.getElementById('tec-nivel').value       = nivel;
    document.getElementById('tec-nivel-valor').textContent = nivel + '%';
    document.getElementById('tec-categoria').value   = categoria;
    document.getElementById('tec-color-desde').value = colorDesde;
    document.getElementById('tec-color-hasta').value = colorHasta;
    document.getElementById('tituloModalTecnologia').textContent = id ? 'Editar Tecnología' : 'Nueva Tecnología';

    // Cargar select de orden con posiciones disponibles
    await cargarSelectOrden('tec-orden', '../php/api/tecnologias.php', 'tecnologias', id ? Number(id) : null, orden);

    modalTecnologia.show();
}

/** Guarda (crea o actualiza) una tecnología */
async function guardarTecnologia() {
    const datos = new FormData();
    datos.append('accion',      document.getElementById('tec-id').value ? 'actualizar' : 'crear');
    datos.append('id',          document.getElementById('tec-id').value);
    datos.append('nombre',      document.getElementById('tec-nombre').value);
    datos.append('nivel',       document.getElementById('tec-nivel').value);
    datos.append('categoria',   document.getElementById('tec-categoria').value);
    datos.append('color_desde', document.getElementById('tec-color-desde').value);
    datos.append('color_hasta', document.getElementById('tec-color-hasta').value);
    datos.append('orden',       document.getElementById('tec-orden').value || 0);

    try {
        const res = await ajax('../php/api/tecnologias.php', datos);
        modalTecnologia.hide();
        if (res.ok) cargarTablaTecnologias();
        else alert('Error: ' + (res.error || 'No se pudo guardar.'));
    } catch (e) {
        alert('Error de conexión.');
    }
}

/** Elimina una tecnología previa confirmación */
function eliminarTecnologia(id) {
    confirmarEliminar(async () => {
        const datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('id', id);
        const res = await ajax('../php/api/tecnologias.php', datos);
        if (res.ok) cargarTablaTecnologias();
        else alert('Error al eliminar.');
    });
}

// ============================================================
// SECCIÓN: PROYECTOS — CRUD
// ============================================================

/** Carga y renderiza la tabla de proyectos */
async function cargarTablaProyectos() {
    const contenedor = document.getElementById('tabla-proyectos');
    try {
        const data  = await ajax('../php/api/proyectos.php');
        const items = data.proyectos || [];

        if (items.length === 0) {
            contenedor.innerHTML = '<p class="text-muted text-center py-3">No hay proyectos registrados.</p>';
            return;
        }

        let html = `
            <div class="table-responsive">
            <table class="tabla-dash">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th class="col-ocultar-mobile">Tags</th>
                        <th class="col-ocultar-mobile">Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;

        items.forEach(p => {
            // Renderizar tags como badges
            const tagsHTML = (p.tags || '').split(',')
                .filter(t => t.trim())
                .map(t => `<span class="tag">${esc(t.trim())}</span>`)
                .join('');

            html += `
                <tr>
                    <td>
                        <strong>${esc(p.titulo)}</strong>
                        <div class="text-muted small">${esc(p.descripcion?.substring(0,60))}...</div>
                    </td>
                    <td class="col-ocultar-mobile">
                        <div class="proyecto-tags">${tagsHTML}</div>
                    </td>
                    <td class="col-ocultar-mobile">${p.orden}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-editar"
                                onclick="abrirModalProyecto(${p.id})">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                            <button class="btn-eliminar" onclick="eliminarProyecto(${p.id})">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        contenedor.innerHTML = html;

    } catch (e) {
        contenedor.innerHTML = '<p class="text-danger">Error al cargar proyectos.</p>';
    }
}

/**
 * Abre el modal de proyecto
 * Si se pasa id, carga los datos del proyecto para edición
 */
async function abrirModalProyecto(id = '') {
    // Limpiar campos
    document.getElementById('proy-id').value          = id;
    document.getElementById('proy-titulo').value      = '';
    document.getElementById('proy-descripcion').value = '';
    document.getElementById('proy-demo').value        = '';
    document.getElementById('proy-github').value      = '';
    document.getElementById('proy-tags').value        = '';
    document.getElementById('proy-imagen').value      = '';
    document.getElementById('tituloModalProyecto').textContent = id ? 'Editar Proyecto' : 'Nuevo Proyecto';

    let ordenActual = 0;

    // Si es edición, cargar datos del proyecto primero
    if (id) {
        try {
            const datos = new FormData();
            datos.append('accion', 'obtener');
            datos.append('id', id);
            const data = await ajax('../php/api/proyectos.php', datos);
            const p = data.proyecto || {};

            document.getElementById('proy-titulo').value      = p.titulo || '';
            document.getElementById('proy-descripcion').value = p.descripcion || '';
            document.getElementById('proy-demo').value        = p.demo_url || '';
            document.getElementById('proy-github').value      = p.github_url || '';
            document.getElementById('proy-tags').value        = p.tags || '';
            document.getElementById('proy-imagen').value      = p.imagen_url || '';
            ordenActual = p.orden || 0;
        } catch (e) {
            console.error('Error al cargar proyecto:', e);
        }
    }

    // Cargar select de orden con posiciones disponibles
    await cargarSelectOrden('proy-orden', '../php/api/proyectos.php', 'proyectos', id ? Number(id) : null, ordenActual);

    modalProyecto.show();
}

/** Guarda (crea o actualiza) un proyecto */
async function guardarProyecto() {
    const datos = new FormData();
    datos.append('accion',      document.getElementById('proy-id').value ? 'actualizar' : 'crear');
    datos.append('id',          document.getElementById('proy-id').value);
    datos.append('titulo',      document.getElementById('proy-titulo').value);
    datos.append('descripcion', document.getElementById('proy-descripcion').value);
    datos.append('demo_url',    document.getElementById('proy-demo').value);
    datos.append('github_url',  document.getElementById('proy-github').value);
    datos.append('tags',        document.getElementById('proy-tags').value);
    datos.append('imagen_url',  document.getElementById('proy-imagen').value);
    datos.append('orden',       document.getElementById('proy-orden').value || 0);

    try {
        const res = await ajax('../php/api/proyectos.php', datos);
        modalProyecto.hide();
        if (res.ok) cargarTablaProyectos();
        else alert('Error: ' + (res.error || 'No se pudo guardar.'));
    } catch (e) {
        alert('Error de conexión.');
    }
}

/** Elimina un proyecto previa confirmación */
function eliminarProyecto(id) {
    confirmarEliminar(async () => {
        const datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('id', id);
        const res = await ajax('../php/api/proyectos.php', datos);
        if (res.ok) cargarTablaProyectos();
        else alert('Error al eliminar.');
    });
}

// ============================================================
// SECCIÓN: MENSAJES RECIBIDOS
// ============================================================

/** Carga y renderiza los mensajes de contacto */
async function cargarTablaMensajes() {
    const contenedor = document.getElementById('tabla-mensajes');
    try {
        const data  = await ajax('../php/api/mensajes.php');
        const items = data.mensajes || [];

        if (items.length === 0) {
            contenedor.innerHTML = '<p class="text-muted text-center py-3">No hay mensajes recibidos.</p>';
            return;
        }

        let html = `
            <div class="table-responsive">
            <table class="tabla-dash">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th class="col-ocultar-mobile">Asunto</th>
                        <th class="col-ocultar-mobile">Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;

        items.forEach(m => {
            const noLeido = m.leido == 0;
            html += `
                <tr class="${noLeido ? 'mensaje-no-leido' : ''}">
                    <td>
                        <!-- Indicador visual de no leído -->
                        <span class="estado-leido ${noLeido ? 'estado-no-leido' : ''}" title="${noLeido ? 'No leído' : 'Leído'}"></span>
                    </td>
                    <td><strong>${esc(m.nombre)}</strong></td>
                    <td>${esc(m.email)}</td>
                    <td class="col-ocultar-mobile">${esc(m.asunto)}</td>
                    <td class="col-ocultar-mobile text-muted small">${m.recibido_en}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-editar" onclick="verMensaje(${m.id})">
                                <i class="bi bi-eye"></i> Ver
                            </button>
                            <button class="btn-eliminar" onclick="eliminarMensaje(${m.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        contenedor.innerHTML = html;

    } catch (e) {
        contenedor.innerHTML = '<p class="text-danger">Error al cargar mensajes.</p>';
    }
}

/** Muestra el contenido completo de un mensaje y lo marca como leído */
async function verMensaje(id) {
    try {
        const datos = new FormData();
        datos.append('accion', 'ver');
        datos.append('id', id);
        const data = await ajax('../php/api/mensajes.php', datos);
        const m = data.mensaje;
        if (m) {
            // Mostrar mensaje en un alert nativo (simple y funcional)
            alert(`De: ${m.nombre} <${m.email}>\nAsunto: ${m.asunto}\n\n${m.mensaje}`);
            cargarTablaMensajes(); // Recargar para actualizar estado "leído"
        }
    } catch (e) {
        alert('Error al cargar el mensaje.');
    }
}

/** Elimina un mensaje previa confirmación */
function eliminarMensaje(id) {
    confirmarEliminar(async () => {
        const datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('id', id);
        const res = await ajax('../php/api/mensajes.php', datos);
        if (res.ok) cargarTablaMensajes();
        else alert('Error al eliminar.');
    });
}

// ============================================================
// UTILIDAD: Escapar HTML para prevenir XSS
// Convierte caracteres especiales en entidades HTML
// ============================================================
function esc(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
