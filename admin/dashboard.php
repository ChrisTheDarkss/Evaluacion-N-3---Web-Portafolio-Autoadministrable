<?php
// ============================================================
// admin/dashboard.php — Panel de administración principal
// Requiere que el usuario esté autenticado (sesión activa)
// ============================================================

// Incluimos el sistema de autenticación
require_once '../php/auth.php';

// Si no está autenticado, redirige al inicio
requiereLogin();

// Conexión a la base de datos
require_once '../php/config.php';
$db = getDB();

// ---- Contadores para las tarjetas del dashboard ----
$total_proyectos   = $db->query("SELECT COUNT(*) FROM proyectos")->fetchColumn();
$total_habilidades = $db->query("SELECT COUNT(*) FROM habilidades")->fetchColumn();
$total_tecnologias = $db->query("SELECT COUNT(*) FROM tecnologias")->fetchColumn();
$total_mensajes    = $db->query("SELECT COUNT(*) FROM contacto WHERE leido = 0")->fetchColumn();

// Nombre del admin en sesión
$admin_nombre = $_SESSION['usuario_nombre'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Panel Administrativo</title>

    <!-- Bootstrap 5.3.8 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- CSS del dashboard -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="dashboard-body">

<!-- ============================================================
     SIDEBAR — Menú lateral de navegación
============================================================ -->
<aside class="sidebar" id="sidebar">

    <!-- Logo / Título del panel -->
    <div class="sidebar-header">
        <div class="nav-logo"><span>AD</span></div>
        <span class="sidebar-title">Panel Admin</span>
        <!-- Botón para cerrar sidebar en mobile -->
        <button class="sidebar-close d-md-none" id="btnCerrarSidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <!-- Nombre del administrador -->
    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="bi bi-person-fill"></i>
        </div>
        <div>
            <div class="user-nombre"><?= htmlspecialchars($admin_nombre) ?></div>
            <div class="user-rol">Administrador</div>
        </div>
    </div>

    <!-- Links de navegación del panel -->
    <nav class="sidebar-nav">
        <ul class="list-unstyled">
            <!-- Inicio del dashboard -->
            <li>
                <a href="#seccion-inicio" class="sidebar-link active" data-seccion="inicio">
                    <i class="bi bi-speedometer2"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <!-- Gestión de biografía -->
            <li>
                <a href="#seccion-biografia" class="sidebar-link" data-seccion="biografia">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Biografía</span>
                </a>
            </li>
            <!-- Gestión de habilidades -->
            <li>
                <a href="#seccion-habilidades" class="sidebar-link" data-seccion="habilidades">
                    <i class="bi bi-tools"></i>
                    <span>Habilidades</span>
                </a>
            </li>
            <!-- Gestión de tecnologías -->
            <li>
                <a href="#seccion-tecnologias" class="sidebar-link" data-seccion="tecnologias">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Tecnologías</span>
                </a>
            </li>
            <!-- Gestión de proyectos -->
            <li>
                <a href="#seccion-proyectos" class="sidebar-link" data-seccion="proyectos">
                    <i class="bi bi-folder-fill"></i>
                    <span>Proyectos</span>
                </a>
            </li>
            <!-- Mensajes recibidos por el formulario de contacto -->
            <li>
                <a href="#seccion-mensajes" class="sidebar-link" data-seccion="mensajes">
                    <i class="bi bi-envelope-fill"></i>
                    <span>Mensajes</span>
                    <?php if ($total_mensajes > 0): ?>
                        <!-- Badge que muestra cuántos mensajes no leídos hay -->
                        <span class="badge-notif"><?= $total_mensajes ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Botones de acción al final del sidebar -->
    <div class="sidebar-footer">
        <!-- Enlace al portafolio público -->
        <a href="../index.php" target="_blank" class="sidebar-link">
            <i class="bi bi-eye-fill"></i>
            <span>Ver Portafolio</span>
        </a>
        <!-- Cerrar sesión -->
        <a href="../php/logout.php" class="sidebar-link sidebar-link-danger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</aside>

<!-- ============================================================
     CONTENIDO PRINCIPAL
============================================================ -->
<main class="dashboard-main" id="dashboardMain">

    <!-- ---- Topbar (header del contenido) ---- -->
    <header class="dash-topbar">
        <!-- Botón para abrir/cerrar sidebar en mobile -->
        <button class="btn-menu-toggle d-md-none" id="btnAbrirSidebar">
            <i class="bi bi-list"></i>
        </button>
        <h4 class="dash-page-title" id="pageTitle">Inicio</h4>
        <div class="topbar-acciones">
            <a href="../index.php" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-eye me-1"></i> Ver sitio
            </a>
        </div>
    </header>

    <!-- ====================================================
         SECCIÓN: INICIO — Tarjetas de resumen
    ==================================================== -->
    <section id="seccion-inicio" class="dash-seccion activa">
        <div class="row g-4 mb-5">

            <!-- Tarjeta: Proyectos -->
            <div class="col-6 col-md-3">
                <div class="stat-card" style="--accent: #3b82f6">
                    <div class="stat-icono"><i class="bi bi-folder-fill"></i></div>
                    <div class="stat-numero"><?= $total_proyectos ?></div>
                    <div class="stat-label">Proyectos</div>
                </div>
            </div>

            <!-- Tarjeta: Habilidades -->
            <div class="col-6 col-md-3">
                <div class="stat-card" style="--accent: #8b5cf6">
                    <div class="stat-icono"><i class="bi bi-tools"></i></div>
                    <div class="stat-numero"><?= $total_habilidades ?></div>
                    <div class="stat-label">Habilidades</div>
                </div>
            </div>

            <!-- Tarjeta: Tecnologías -->
            <div class="col-6 col-md-3">
                <div class="stat-card" style="--accent: #22c55e">
                    <div class="stat-icono"><i class="bi bi-bar-chart-fill"></i></div>
                    <div class="stat-numero"><?= $total_tecnologias ?></div>
                    <div class="stat-label">Tecnologías</div>
                </div>
            </div>

            <!-- Tarjeta: Mensajes sin leer -->
            <div class="col-6 col-md-3">
                <div class="stat-card" style="--accent: #f59e0b">
                    <div class="stat-icono"><i class="bi bi-envelope-fill"></i></div>
                    <div class="stat-numero"><?= $total_mensajes ?></div>
                    <div class="stat-label">Sin leer</div>
                </div>
            </div>
        </div>

        <!-- Accesos rápidos a las secciones de gestión -->
        <h5 class="text-white mb-3">Accesos Rápidos</h5>
        <div class="row g-3">
            <div class="col-md-3 col-6">
                <button class="acceso-rapido" data-seccion="biografia">
                    <i class="bi bi-person-lines-fill"></i>
                    <span>Editar Biografía</span>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="acceso-rapido" data-seccion="habilidades">
                    <i class="bi bi-tools"></i>
                    <span>Habilidades</span>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="acceso-rapido" data-seccion="tecnologias">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Tecnologías</span>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="acceso-rapido" data-seccion="proyectos">
                    <i class="bi bi-folder-fill"></i>
                    <span>Proyectos</span>
                </button>
            </div>
        </div>
    </section>

    <!-- ====================================================
         SECCIÓN: BIOGRAFÍA
    ==================================================== -->
    <section id="seccion-biografia" class="dash-seccion">
        <div class="dash-card">
            <div class="dash-card-header">
                <h5><i class="bi bi-person-lines-fill me-2"></i>Editar Biografía</h5>
            </div>
            <div class="dash-card-body">
                <!-- Mensaje de feedback -->
                <div id="bio-feedback" class="alert d-none"></div>

                <!-- Formulario de edición de biografía (se carga dinámicamente) -->
                <div id="bio-form-container">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-2">Cargando datos...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         SECCIÓN: HABILIDADES — CRUD completo
    ==================================================== -->
    <section id="seccion-habilidades" class="dash-seccion">
        <div class="dash-card">
            <div class="dash-card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-tools me-2"></i>Habilidades y Herramientas</h5>
                <!-- Botón para abrir modal de nueva habilidad -->
                <button class="btn-dash-primary" data-bs-toggle="modal" data-bs-target="#modalHabilidad" onclick="abrirModalHabilidad()">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Habilidad
                </button>
            </div>
            <div class="dash-card-body">
                <div id="tabla-habilidades">
                    <!-- La tabla se carga con AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         SECCIÓN: TECNOLOGÍAS — CRUD completo
    ==================================================== -->
    <section id="seccion-tecnologias" class="dash-seccion">
        <div class="dash-card">
            <div class="dash-card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-bar-chart-fill me-2"></i>Tecnologías Dominadas</h5>
                <button class="btn-dash-primary" data-bs-toggle="modal" data-bs-target="#modalTecnologia" onclick="abrirModalTecnologia()">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Tecnología
                </button>
            </div>
            <div class="dash-card-body">
                <div id="tabla-tecnologias">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         SECCIÓN: PROYECTOS — CRUD completo
    ==================================================== -->
    <section id="seccion-proyectos" class="dash-seccion">
        <div class="dash-card">
            <div class="dash-card-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-folder-fill me-2"></i>Proyectos Realizados</h5>
                <button class="btn-dash-primary" data-bs-toggle="modal" data-bs-target="#modalProyecto" onclick="abrirModalProyecto()">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Proyecto
                </button>
            </div>
            <div class="dash-card-body">
                <div id="tabla-proyectos">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         SECCIÓN: MENSAJES recibidos
    ==================================================== -->
    <section id="seccion-mensajes" class="dash-seccion">
        <div class="dash-card">
            <div class="dash-card-header">
                <h5><i class="bi bi-envelope-fill me-2"></i>Mensajes Recibidos</h5>
            </div>
            <div class="dash-card-body">
                <div id="tabla-mensajes">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- fin dashboard-main -->

<!-- ============================================================
     MODAL: HABILIDAD — Crear / Editar
============================================================ -->
<div class="modal fade" id="modalHabilidad" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="tituloModalHabilidad">Nueva Habilidad</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- ID oculto para modo edición (vacío = crear) -->
                <input type="hidden" id="hab-id">

                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" id="hab-nombre" class="form-input" placeholder="Ej: JavaScript" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Ícono Bootstrap Icons</label>
                    <input type="text" id="hab-icono" class="form-input" placeholder="Ej: bi bi-filetype-js">
                    <small class="text-muted">Busca íconos en <a href="https://icons.getbootstrap.com" target="_blank" class="text-primary">icons.getbootstrap.com</a></small>
                </div>

                <!-- Selección de colores del gradiente del ícono -->
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-white">Color inicio</label>
                        <input type="color" id="hab-color-desde" class="form-input form-input-color" value="#3b82f6">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-white">Color fin</label>
                        <input type="color" id="hab-color-hasta" class="form-input form-input-color" value="#8b5cf6">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Orden de aparición</label>
                    <select id="hab-orden" class="form-input"></select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-dash-primary" onclick="guardarHabilidad()">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL: TECNOLOGÍA — Crear / Editar
============================================================ -->
<div class="modal fade" id="modalTecnologia" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="tituloModalTecnologia">Nueva Tecnología</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tec-id">

                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" id="tec-nombre" class="form-input" placeholder="Ej: PHP">
                </div>

                <!-- Barra deslizante para definir el nivel de dominio -->
                <div class="mb-3">
                    <label class="form-label text-white">
                        Nivel de dominio: <span id="tec-nivel-valor" class="text-primary fw-bold">75%</span>
                    </label>
                    <input type="range" id="tec-nivel" class="form-range" min="0" max="100" value="75"
                           oninput="document.getElementById('tec-nivel-valor').textContent = this.value + '%'">
                </div>

                <!-- Categoría de la tecnología -->
                <div class="mb-3">
                    <label class="form-label text-white">Categoría</label>
                    <select id="tec-categoria" class="form-input">
                        <option value="Frontend">Frontend</option>
                        <option value="Backend">Backend</option>
                        <option value="Base de Datos">Base de Datos</option>
                        <option value="Herramientas">Herramientas</option>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-white">Color inicio</label>
                        <input type="color" id="tec-color-desde" class="form-input form-input-color" value="#3b82f6">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-white">Color fin</label>
                        <input type="color" id="tec-color-hasta" class="form-input form-input-color" value="#06b6d4">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Orden</label>
                    <select id="tec-orden" class="form-input"></select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-dash-primary" onclick="guardarTecnologia()">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL: PROYECTO — Crear / Editar
============================================================ -->
<div class="modal fade" id="modalProyecto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="tituloModalProyecto">Nuevo Proyecto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="proy-id">

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-white">Título</label>
                        <input type="text" id="proy-titulo" class="form-input" placeholder="Nombre del proyecto">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-white">Descripción</label>
                        <textarea id="proy-descripcion" class="form-input" rows="3" placeholder="Describe brevemente el proyecto..."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white">URL Demo</label>
                        <input type="url" id="proy-demo" class="form-input" placeholder="https://...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white">URL GitHub</label>
                        <input type="url" id="proy-github" class="form-input" placeholder="https://github.com/...">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label text-white">Tags <small class="text-muted">(separados por coma)</small></label>
                        <input type="text" id="proy-tags" class="form-input" placeholder="PHP, MySQL, Bootstrap">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white">Orden</label>
                        <select id="proy-orden" class="form-input"></select>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-white">URL Imagen</label>
                        <input type="text" id="proy-imagen" class="form-input" placeholder="img/proyecto.png">
                        <small class="text-muted">Sube la imagen a la carpeta /img/ del servidor primero</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-dash-primary" onclick="guardarProyecto()">
                    <i class="bi bi-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     MODAL: CONFIRMAR ELIMINACIÓN
============================================================ -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modal-dark text-center">
            <div class="modal-body py-4">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-1 mb-3 d-block"></i>
                <h6 class="text-white mb-2">¿Eliminar este registro?</h6>
                <p class="text-muted small mb-4">Esta acción no se puede deshacer.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger btn-sm" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS del dashboard -->
<script src="../js/dashboard.js"></script>
</body>
</html>
