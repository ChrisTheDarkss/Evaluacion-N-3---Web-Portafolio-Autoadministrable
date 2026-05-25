# Portafolio Estudiantil Interactivo

Proyecto de portafolio web dinámico y autoadministrable desarrollado con PHP, MySQL, Bootstrap 5 y JavaScript.

## 🌐 Proyecto en Producción
[https://teclab.uct.cl/~cescobar/](https://teclab.uct.cl/~cescobar/)

## 🛠️ Tecnologías Utilizadas
- HTML5 + CSS3
- Bootstrap 5.3.8
- JavaScript (AJAX + JSON)
- PHP + MySQL (PDO)
- Git & GitHub
- IA como apoyo al desarrollo

## 📁 Estructura del Proyecto
```
portafolio/
├── index.php                  ← Página principal del portafolio
├── bd.sql                     ← Script SQL para crear las tablas
├── .gitignore                 ← Archivos excluidos de Git
├── README.md                  ← Este archivo
├── css/
│   ├── style.css              ← Estilos del portafolio público
│   └── dashboard.css          ← Estilos del panel administrativo
├── js/
│   ├── main.js                ← JS del portafolio (animaciones, AJAX)
│   └── dashboard.js           ← JS del dashboard (CRUD)
├── php/
│   ├── config.php             ← Configuración BD (sin contraseñas)
│   ├── config.local.php       ← ⚠️ Credenciales (NO está en GitHub)
│   ├── auth.php               ← Sistema de autenticación
│   ├── login.php              ← Endpoint login
│   ├── logout.php             ← Cierre de sesión
│   ├── contacto.php           ← Endpoint formulario de contacto
│   └── api/
│       ├── biografia.php      ← API CRUD biografía
│       ├── habilidades.php    ← API CRUD habilidades
│       ├── tecnologias.php    ← API CRUD tecnologías
│       ├── proyectos.php      ← API CRUD proyectos
│       └── mensajes.php       ← API mensajes de contacto
├── admin/
│   └── dashboard.php          ← Panel administrativo
├── docs/
│   └── uso_ia.md              ← Documento de uso de IA
└── img/                       ← Imágenes del portafolio
```

## ⚙️ Instalación en el Servidor

### 1. Clonar o descargar el proyecto
```bash
git clone https://github.com/ChrisTheDarkss/Evaluacion-N-3---Web-Portafolio-Autoadministrable.git
```

### 2. Importar la base de datos
- Abrir phpMyAdmin
- Seleccionar la base de datos `cescobar_db1`
- Ir a **Importar** y subir el archivo `bd.sql`

### 3. Crear el archivo de credenciales
Crear el archivo `php/config.local.php` **directamente en el servidor** vía FileZilla:
```php
<?php
define('DB_USER', 'tu_usuario_mysql');
define('DB_PASS', 'tu_contraseña_mysql');
```
> ⚠️ Este archivo NO está en GitHub por seguridad. Debes crearlo manualmente en el servidor.

### 4. Subir archivos al servidor
- Conectarse a `teclab.uct.cl` con FileZilla
- Subir todos los archivos del proyecto
- Subir además `config.local.php` a la carpeta `php/`

### 5. Acceder al panel admin
- URL: `https://teclab.uct.cl/~cescobar/admin/dashboard.php`
- Usuario: `admin`
- Contraseña: `admin123`
- ⚠️ Cambiar la contraseña después del primer acceso

## 🎨 Diseño Figma
[https://www.figma.com/make/8cHgabMSBHJWhLwmW7iMef/Perfil-Estudiante-Interactivo?p=f&t=1fnYzFIko7up58cq-0](#) 

## 🤖 Uso de IA
Ver documento `docs/uso_ia.md`
