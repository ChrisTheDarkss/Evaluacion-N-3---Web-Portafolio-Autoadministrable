-- ============================================================
-- bd.sql — Script de Base de Datos
-- Portafolio Estudiantil Interactivo
-- Base de datos: cescobar_db1 (ya creada en el servidor)
-- INSTRUCCIONES: Importar directamente desde phpMyAdmin
--                seleccionando la base de datos cescobar_db1
-- ============================================================

-- Aseguramos el juego de caracteres correcto para tildes y ñ
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ============================================================
-- TABLA: biografia
-- Almacena los datos personales del estudiante (1 sola fila)
-- ============================================================
CREATE TABLE IF NOT EXISTS biografia (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100) NOT NULL,
    titulo        VARCHAR(150) NOT NULL,
    ubicacion     VARCHAR(100),
    descripcion   TEXT,
    descripcion2  TEXT,
    email         VARCHAR(100),
    telefono      VARCHAR(30),
    github_url    VARCHAR(255),
    linkedin_url  VARCHAR(255),
    twitter_url   VARCHAR(255),
    avatar_url    VARCHAR(255) DEFAULT 'img/avatar.png',
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: habilidades
-- Íconos/tarjetas de habilidades que aparecen en el portafolio
-- ============================================================
CREATE TABLE IF NOT EXISTS habilidades (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(80)  NOT NULL,
    icono        VARCHAR(100) DEFAULT 'bi bi-code-slash',
    color_desde  VARCHAR(30)  DEFAULT '#3b82f6',
    color_hasta  VARCHAR(30)  DEFAULT '#8b5cf6',
    orden        INT          DEFAULT 0
);

-- ============================================================
-- TABLA: tecnologias
-- Barras de progreso con nivel de dominio por tecnología
-- ============================================================
CREATE TABLE IF NOT EXISTS tecnologias (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(80)  NOT NULL,
    nivel        INT          NOT NULL DEFAULT 50,
    categoria    ENUM('Frontend','Backend','Base de Datos','Herramientas') DEFAULT 'Frontend',
    color_desde  VARCHAR(30)  DEFAULT '#3b82f6',
    color_hasta  VARCHAR(30)  DEFAULT '#06b6d4',
    orden        INT          DEFAULT 0
);

-- ============================================================
-- TABLA: proyectos
-- Tarjetas de proyectos realizados con enlaces a demo y GitHub
-- ============================================================
CREATE TABLE IF NOT EXISTS proyectos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    titulo       VARCHAR(150) NOT NULL,
    descripcion  TEXT,
    imagen_url   VARCHAR(255) DEFAULT 'img/proyecto_default.png',
    demo_url     VARCHAR(255),
    github_url   VARCHAR(255),
    tags         VARCHAR(255),
    orden        INT          DEFAULT 0,
    creado_en    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: contacto
-- Guarda los mensajes enviados desde el formulario de contacto
-- ============================================================
CREATE TABLE IF NOT EXISTS contacto (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    email        VARCHAR(100) NOT NULL,
    asunto       VARCHAR(200),
    mensaje      TEXT         NOT NULL,
    leido        TINYINT(1)   DEFAULT 0,
    recibido_en  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- TABLA: usuarios
-- Credenciales de acceso al panel administrativo
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombre        VARCHAR(100),
    creado_en     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- DATOS DE EJEMPLO
-- Puedes modificarlos desde el Dashboard una vez instalado
-- ============================================================

-- Biografía inicial
INSERT INTO biografia (nombre, titulo, ubicacion, descripcion, descripcion2, email, telefono, github_url, linkedin_url)
VALUES (
    'Juan Pérez González',
    'Desarrollador Web Full Stack',
    'Temuco, Chile',
    'Estudiante apasionado de desarrollo web con experiencia en la creación de aplicaciones modernas y responsivas. Me especializo en tecnologías frontend y backend, con un enfoque en crear experiencias de usuario intuitivas y eficientes.',
    'Constantemente aprendiendo nuevas tecnologías y mejores prácticas para mantenerme actualizado en el dinámico mundo del desarrollo web. Mi objetivo es crear soluciones innovadoras que resuelvan problemas reales y mejoren la vida de las personas.',
    'estudiante@ejemplo.cl',
    '+56 9 1234 5678',
    'https://github.com/username',
    'https://linkedin.com/in/username'
);

-- Habilidades
INSERT INTO habilidades (nombre, icono, color_desde, color_hasta, orden) VALUES
('HTML5',       'bi bi-filetype-html',  '#f97316', '#ef4444', 1),
('CSS3',        'bi bi-filetype-css',   '#3b82f6', '#06b6d4', 2),
('JavaScript',  'bi bi-filetype-js',    '#eab308', '#f59e0b', 3),
('PHP',         'bi bi-filetype-php',   '#8b5cf6', '#6366f1', 4),
('MySQL',       'bi bi-database-fill',  '#2563eb', '#60a5fa', 5),
('Bootstrap',   'bi bi-bootstrap-fill', '#7c3aed', '#a78bfa', 6),
('GitHub',      'bi bi-github',         '#374151', '#6b7280', 7),
('IA Web Dev',  'bi bi-cpu-fill',       '#16a34a', '#34d399', 8);

-- Tecnologías
INSERT INTO tecnologias (nombre, nivel, categoria, color_desde, color_hasta, orden) VALUES
('HTML5 & CSS3',  90, 'Frontend',      '#f97316', '#ef4444', 1),
('JavaScript',    80, 'Frontend',      '#eab308', '#f59e0b', 2),
('Bootstrap',     85, 'Frontend',      '#7c3aed', '#a78bfa', 3),
('PHP',           75, 'Backend',       '#8b5cf6', '#6366f1', 4),
('MySQL',         70, 'Base de Datos', '#2563eb', '#60a5fa', 5),
('Git & GitHub',  85, 'Herramientas',  '#374151', '#6b7280', 6),
('AJAX & JSON',   65, 'Backend',       '#16a34a', '#34d399', 7),
('IA Aplicada',   80, 'Herramientas',  '#0891b2', '#22d3ee', 8);

-- Proyectos de ejemplo
INSERT INTO proyectos (titulo, descripcion, demo_url, github_url, tags, orden) VALUES
('E-Commerce Platform',     'Plataforma de comercio electrónico con carrito de compras y panel de administración.',   'https://demo.example.com', 'https://github.com/username/ecommerce', 'PHP,MySQL,Bootstrap,JavaScript', 1),
('Gestor de Tareas',        'Aplicación de gestión de tareas con categorías, prioridades y seguimiento de progreso.', 'https://demo.example.com', 'https://github.com/username/taskapp',  'PHP,MySQL,AJAX,Bootstrap',        2),
('Dashboard Meteorológico', 'Dashboard interactivo con pronósticos y gráficos usando APIs de clima externas.',         'https://demo.example.com', 'https://github.com/username/weather',  'JavaScript,API,Bootstrap,CSS3',   3),
('Blog Personal',           'Plataforma de blogging con editor de contenido, comentarios y búsqueda.',                'https://demo.example.com', 'https://github.com/username/blog',     'PHP,MySQL,Bootstrap,JavaScript',  4);

-- Usuario administrador
-- Contraseña por defecto: admin123 — CAMBIA ESTO DESPUÉS DE INSTALAR
-- Para cambiar: genera un hash con password_hash('nueva_clave', PASSWORD_BCRYPT)
INSERT INTO usuarios (username, password_hash, nombre)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');

-- ============================================================
-- FIN DEL SCRIPT
-- ============================================================
