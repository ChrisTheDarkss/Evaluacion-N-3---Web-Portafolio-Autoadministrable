<?php
// ============================================================
// php/config.php — Configuración general de la base de datos
// ============================================================
// IMPORTANTE: Este archivo SÍ se sube a GitHub.
//             NO contiene contraseñas.
//             Las credenciales están en config.local.php
// ============================================================

// Servidor de base de datos
define('DB_HOST',    'localhost');

// Nombre de la base de datos asignada en teclab
define('DB_NAME',    'cescobar_db1');

// Juego de caracteres (soporta tildes, ñ y emojis)
define('DB_CHARSET', 'utf8mb4');

// Zona horaria de Chile
date_default_timezone_set('America/Santiago');

// ============================================================
// Carga las credenciales desde config.local.php
// Ese archivo SOLO existe en el servidor (no está en GitHub)
// ============================================================
$config_local = __DIR__ . '/config.local.php';

if (!file_exists($config_local)) {
    // Si no existe el archivo local, muestra error claro
    die(json_encode([
        'error' => 'Falta el archivo php/config.local.php con las credenciales. Ver instrucciones en README.md'
    ]));
}

require_once $config_local;

// ============================================================
// FUNCIÓN: getDB()
// Devuelve una conexión PDO reutilizable (patrón singleton)
// Se crea solo la primera vez que se llama
// ============================================================
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        // Cadena de conexión DSN
        $dsn = "mysql:host=" . DB_HOST
             . ";dbname=" . DB_NAME
             . ";charset=" . DB_CHARSET;

        // Opciones de PDO para manejo correcto de errores
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Resultados como arrays asociativos
            PDO::ATTR_EMULATE_PREPARES   => false,                   // Usar prepared statements reales
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Error de conexión a la base de datos.']));
        }
    }

    return $pdo;
}
