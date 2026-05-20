<?php
// ============================================================
// index.php — Portafolio Estudiantil
// ============================================================
require_once 'php/config.php';
require_once 'php/auth.php';

$db = getDB();

// Cargar datos dinámicos desde la BD
$bio         = $db->query("SELECT * FROM biografia LIMIT 1")->fetch();
$habilidades = $db->query("SELECT * FROM habilidades ORDER BY orden ASC")->fetchAll();
$tecnologias = $db->query("SELECT * FROM tecnologias ORDER BY orden ASC")->fetchAll();
$proyectos   = $db->query("SELECT * FROM proyectos ORDER BY orden ASC")->fetchAll();

$iniciales = '';
if ($bio) {
    $partes = explode(' ', trim($bio['nombre']));
    foreach (array_slice($partes, 0, 2) as $p) $iniciales .= strtoupper($p[0]);
}

$autenticado = estaAutenticado();
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($bio['nombre'] ?? 'Portafolio') ?> — Portafolio</title>

    <!-- Bootstrap 5.3.8 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- CSS propio -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<nav class="navbar navbar-expand-md fixed-top" id="navbar">
    <div class="container">
        <!-- Logo / Iniciales -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <div class="nav-logo">
                <span><?= htmlspecialchars($iniciales ?: 'ES') ?></span>
            </div>
            <span class="nav-nombre"><?= htmlspecialchars(explode(' ', $bio['nombre'] ?? 'Estudiante')[0]) ?></span>
        </a>

        <!-- Hamburguesa mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list text-white fs-4"></i>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-md-2">
                <li class="nav-item"><a class="nav-link" href="#biografia">Biografía</a></li>
                <li class="nav-item"><a class="nav-link" href="#habilidades">Habilidades/Herramientas</a></li>
                <li class="nav-item"><a class="nav-link" href="#tecnologias">Tecnologías dominadas</a></li>
                <li class="nav-item"><a class="nav-link" href="#proyectos">Proyectos</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if ($autenticado): ?>
                    <a href="admin/dashboard.php" class="btn btn-sm btn-outline-light me-1">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="php/logout.php" class="btn btn-sm btn-danger">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <button class="btn-login" data-bs-toggle="modal" data-bs-target="#modalLogin">
                        Inicio de Sesión
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- ============================================================
     SECCIÓN: BIOGRAFÍA
============================================================ -->
<section id="biografia" class="seccion-bio">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Avatar -->
            <div class="col-md-5 text-center">
                <div class="avatar-wrapper">
                    <div class="avatar-circle">
                        <?php if (!empty($bio['avatar_url']) && file_exists($bio['avatar_url'])): ?>
                            <img src="<?= htmlspecialchars($bio['avatar_url']) ?>" alt="Avatar" class="avatar-img">
                        <?php else: ?>
                            <span class="avatar-emoji">👨‍💻</span>
                        <?php endif; ?>
                    </div>
                    <div class="avatar-badge"><i class="bi bi-check-lg"></i></div>
                </div>
            </div>

            <!-- Info -->
            <div class="col-md-7">
                <h1 class="bio-nombre"><?= htmlspecialchars($bio['nombre'] ?? 'Tu Nombre') ?></h1>
                <p class="bio-titulo"><?= htmlspecialchars($bio['titulo'] ?? 'Desarrollador Web') ?></p>
                <div class="bio-ubicacion">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span><?= htmlspecialchars($bio['ubicacion'] ?? '') ?></span>
                </div>

                <div class="bio-desc mt-4">
                    <p><?= nl2br(htmlspecialchars($bio['descripcion'] ?? '')) ?></p>
                    <?php if (!empty($bio['descripcion2'])): ?>
                        <p><?= nl2br(htmlspecialchars($bio['descripcion2'])) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Redes sociales -->
                <div class="bio-redes mt-4 d-flex gap-3">
                    <?php if (!empty($bio['github_url'])): ?>
                        <a href="<?= htmlspecialchars($bio['github_url']) ?>" target="_blank" class="btn-red" title="GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($bio['linkedin_url'])): ?>
                        <a href="<?= htmlspecialchars($bio['linkedin_url']) ?>" target="_blank" class="btn-red" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($bio['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($bio['email']) ?>" class="btn-red" title="Email">
                            <i class="bi bi-envelope-fill"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SECCIÓN: HABILIDADES Y HERRAMIENTAS
============================================================ -->
<section id="habilidades" class="seccion">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="seccion-titulo">Habilidades y Herramientas</h2>
            <p class="seccion-subtitulo">Tecnologías y herramientas que domino para crear aplicaciones modernas</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($habilidades as $h): ?>
            <div class="col-6 col-sm-4 col-md-3">
                <div class="skill-card">
                    <div class="skill-icon" style="background: linear-gradient(135deg, <?= htmlspecialchars($h['color_desde']) ?>, <?= htmlspecialchars($h['color_hasta']) ?>)">
                        <i class="<?= htmlspecialchars($h['icono']) ?>"></i>
                    </div>
                    <h5 class="skill-nombre"><?= htmlspecialchars($h['nombre']) ?></h5>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SECCIÓN: TECNOLOGÍAS DOMINADAS
============================================================ -->
<section id="tecnologias" class="seccion seccion-alt">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="seccion-titulo">Tecnologías Dominadas</h2>
            <p class="seccion-subtitulo">Nivel de dominio en diferentes tecnologías y frameworks</p>
        </div>

        <div class="row g-4">
            <?php foreach ($tecnologias as $t): ?>
            <div class="col-md-6">
                <div class="tech-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="tech-nombre"><?= htmlspecialchars($t['nombre']) ?></span>
                            <span class="tech-cat ms-2"><?= htmlspecialchars($t['categoria']) ?></span>
                        </div>
                        <span class="tech-nivel" style="color: <?= htmlspecialchars($t['color_desde']) ?>">
                            <?= (int)$t['nivel'] ?>%
                        </span>
                    </div>
                    <div class="progress tech-progress" role="progressbar" aria-valuenow="<?= (int)$t['nivel'] ?>" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar"
                             style="width: 0%; background: linear-gradient(90deg, <?= htmlspecialchars($t['color_desde']) ?>, <?= htmlspecialchars($t['color_hasta']) ?>);"
                             data-width="<?= (int)$t['nivel'] ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Leyenda de categorías -->
        <div class="d-flex flex-wrap justify-content-center gap-4 mt-5">
            <?php
            $categorias = array_unique(array_column($tecnologias, 'categoria'));
            $colores_cat = [
                'Frontend'       => '#3b82f6',
                'Backend'        => '#16a34a',
                'Base de Datos'  => '#7c3aed',
                'Herramientas'   => '#f97316',
            ];
            foreach ($categorias as $cat):
                $col = $colores_cat[$cat] ?? '#888';
            ?>
            <div class="d-flex align-items-center gap-2">
                <span class="cat-dot" style="background: <?= $col ?>"></span>
                <span class="cat-label"><?= htmlspecialchars($cat) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SECCIÓN: PROYECTOS
============================================================ -->
<section id="proyectos" class="seccion">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="seccion-titulo">Proyectos Realizados</h2>
            <p class="seccion-subtitulo">Muestra de proyectos desarrollados aplicando diferentes tecnologías</p>
        </div>

        <div class="row g-4">
            <?php foreach ($proyectos as $p):
                $tags = array_filter(array_map('trim', explode(',', $p['tags'] ?? '')));
            ?>
            <div class="col-md-6">
                <div class="proyecto-card">
                    <!-- Imagen / placeholder -->
                    <div class="proyecto-img">
                        <?php if (!empty($p['imagen_url']) && file_exists($p['imagen_url'])): ?>
                            <img src="<?= htmlspecialchars($p['imagen_url']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
                        <?php else: ?>
                            <div class="proyecto-placeholder">
                                <i class="bi bi-laptop-fill"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="proyecto-body">
                        <h4 class="proyecto-titulo"><?= htmlspecialchars($p['titulo']) ?></h4>
                        <p class="proyecto-desc"><?= htmlspecialchars($p['descripcion']) ?></p>

                        <!-- Tags -->
                        <div class="proyecto-tags mb-3">
                            <?php foreach ($tags as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-2">
                            <?php if (!empty($p['demo_url'])): ?>
                                <a href="<?= htmlspecialchars($p['demo_url']) ?>" target="_blank" class="btn-demo">
                                    <i class="bi bi-box-arrow-up-right"></i> Demo
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($p['github_url'])): ?>
                                <a href="<?= htmlspecialchars($p['github_url']) ?>" target="_blank" class="btn-codigo">
                                    <i class="bi bi-github"></i> Código
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     SECCIÓN: CONTACTO
============================================================ -->
<section id="contacto" class="seccion seccion-alt">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="seccion-titulo">Contacto</h2>
            <p class="seccion-subtitulo">¿Tienes un proyecto en mente? ¡Hablemos!</p>
        </div>

        <div class="row g-5">
            <!-- Info de contacto -->
            <div class="col-md-5">
                <h4 class="mb-4 text-white">Información de Contacto</h4>
                <div class="d-flex flex-column gap-4">
                    <?php if (!empty($bio['email'])): ?>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="contacto-icono"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contacto-label">Email</div>
                            <div class="contacto-valor"><?= htmlspecialchars($bio['email']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($bio['telefono'])): ?>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="contacto-icono"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contacto-label">Teléfono</div>
                            <div class="contacto-valor"><?= htmlspecialchars($bio['telefono']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($bio['ubicacion'])): ?>
                    <div class="d-flex gap-3 align-items-start">
                        <div class="contacto-icono"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contacto-label">Ubicación</div>
                            <div class="contacto-valor"><?= htmlspecialchars($bio['ubicacion']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="horario-card mt-4">
                    <h6 class="text-white mb-2">Horario de Respuesta</h6>
                    <p class="text-muted mb-0">Lunes a viernes, 9:00 AM – 6:00 PM. Respondo en 24-48 horas.</p>
                </div>
            </div>

            <!-- Formulario -->
            <div class="col-md-7">
                <div class="form-card">
                    <div id="msg-exito" class="alert alert-success d-none" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> ¡Mensaje enviado correctamente!
                    </div>
                    <div id="msg-error" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i> Hubo un error. Intenta de nuevo.
                    </div>

                    <form id="formContacto" novalidate>
                        <div class="mb-3">
                            <label class="form-label text-white">Nombre</label>
                            <input type="text" name="nombre" class="form-input" required placeholder="Tu nombre completo">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Correo Electrónico</label>
                            <input type="email" name="email" class="form-input" required placeholder="tu@correo.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Asunto</label>
                            <input type="text" name="asunto" class="form-input" required placeholder="¿De qué se trata?">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white">Mensaje</label>
                            <textarea name="mensaje" class="form-input" rows="5" required placeholder="Escribe tu mensaje..."></textarea>
                        </div>
                        <button type="submit" class="btn-enviar w-100" id="btnEnviar">
                            <i class="bi bi-send-fill me-2"></i> Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand -->
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="nav-logo"><span><?= htmlspecialchars($iniciales ?: 'ES') ?></span></div>
                    <span class="text-white fw-semibold"><?= htmlspecialchars(explode(' ', $bio['nombre'] ?? '')[0]) ?></span>
                </div>
                <p class="text-muted"><?= htmlspecialchars($bio['titulo'] ?? '') ?></p>
            </div>

            <!-- Links -->
            <div class="col-md-4">
                <h6 class="text-white mb-3">Enlaces Rápidos</h6>
                <ul class="list-unstyled d-flex flex-column gap-2">
                    <li><a href="#biografia" class="footer-link">Biografía</a></li>
                    <li><a href="#habilidades" class="footer-link">Habilidades</a></li>
                    <li><a href="#tecnologias" class="footer-link">Tecnologías</a></li>
                    <li><a href="#proyectos" class="footer-link">Proyectos</a></li>
                    <li><a href="#contacto" class="footer-link">Contacto</a></li>
                </ul>
            </div>

            <!-- Redes -->
            <div class="col-md-4">
                <h6 class="text-white mb-3">Conecta Conmigo</h6>
                <div class="d-flex gap-2 mb-3">
                    <?php if (!empty($bio['github_url'])): ?>
                        <a href="<?= htmlspecialchars($bio['github_url']) ?>" target="_blank" class="btn-red"><i class="bi bi-github"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($bio['linkedin_url'])): ?>
                        <a href="<?= htmlspecialchars($bio['linkedin_url']) ?>" target="_blank" class="btn-red"><i class="bi bi-linkedin"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($bio['twitter_url'])): ?>
                        <a href="<?= htmlspecialchars($bio['twitter_url']) ?>" target="_blank" class="btn-red"><i class="bi bi-twitter-x"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($bio['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($bio['email']) ?>" class="btn-red"><i class="bi bi-envelope-fill"></i></a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($bio['email'])): ?>
                    <p class="text-muted small"><?= htmlspecialchars($bio['email']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="mb-0 text-muted small">
                © <?= date('Y') ?> <?= htmlspecialchars($bio['nombre'] ?? '') ?>. Todos los derechos reservados.
            </p>
            <p class="mb-0 text-muted small">
                Hecho con <i class="bi bi-heart-fill text-danger"></i> y PHP
            </p>
        </div>
    </div>
</footer>

<!-- ============================================================
     MODAL: LOGIN
============================================================ -->
<div class="modal fade" id="modalLogin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white">Inicio de Sesión</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="login-error" class="alert alert-danger d-none"></div>
                <form id="formLogin">
                    <div class="mb-3">
                        <label class="form-label text-white">Usuario</label>
                        <input type="text" name="username" class="form-input" required placeholder="admin">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-white">Contraseña</label>
                        <input type="password" name="password" class="form-input" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn-enviar w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS propio -->
<script src="js/main.js"></script>
</body>
</html>
