<?php
// ============================================================
// php/api/biografia.php
// Endpoint para leer y actualizar la biografía del portafolio
// Acciones: GET (sin acción) = obtener | POST accion=actualizar
// ============================================================
header('Content-Type: application/json');

// Solo el admin autenticado puede modificar datos
require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/config.php';

requiereLogin();

$db     = getDB();
$metodo = $_SERVER['REQUEST_METHOD'];

// ---- GET: Devuelve los datos actuales de la biografía ----
if ($metodo === 'GET') {
    $bio = $db->query("SELECT * FROM biografia LIMIT 1")->fetch();
    echo json_encode(['ok' => true, 'biografia' => $bio ?: new stdClass()]);
    exit;
}

// ---- POST: Actualiza la biografía ----
if ($metodo === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    if ($accion !== 'actualizar') {
        echo json_encode(['ok' => false, 'error' => 'Acción no válida.']);
        exit;
    }

    // Recoger y sanitizar campos del formulario
    $campos = [
        'nombre'       => trim($_POST['nombre']       ?? ''),
        'titulo'       => trim($_POST['titulo']       ?? ''),
        'ubicacion'    => trim($_POST['ubicacion']    ?? ''),
        'email'        => trim($_POST['email']        ?? ''),
        'telefono'     => trim($_POST['telefono']     ?? ''),
        'github_url'   => trim($_POST['github_url']   ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'twitter_url'  => trim($_POST['twitter_url']  ?? ''),
        'descripcion'  => trim($_POST['descripcion']  ?? ''),
        'descripcion2' => trim($_POST['descripcion2'] ?? ''),
    ];

    // Validación mínima: nombre y título son obligatorios
    if (empty($campos['nombre']) || empty($campos['titulo'])) {
        echo json_encode(['ok' => false, 'error' => 'Nombre y título son obligatorios.']);
        exit;
    }

    // Verificar si ya existe un registro de biografía
    $existe = $db->query("SELECT COUNT(*) FROM biografia")->fetchColumn();

    if ($existe) {
        // Actualizar el registro existente
        $sql = "UPDATE biografia SET
                    nombre       = :nombre,
                    titulo       = :titulo,
                    ubicacion    = :ubicacion,
                    email        = :email,
                    telefono     = :telefono,
                    github_url   = :github_url,
                    linkedin_url = :linkedin_url,
                    twitter_url  = :twitter_url,
                    descripcion  = :descripcion,
                    descripcion2 = :descripcion2";
        $db->prepare($sql)->execute($campos);
    } else {
        // Crear el primer registro si no existe
        $sql = "INSERT INTO biografia
                    (nombre, titulo, ubicacion, email, telefono, github_url, linkedin_url, twitter_url, descripcion, descripcion2)
                VALUES
                    (:nombre, :titulo, :ubicacion, :email, :telefono, :github_url, :linkedin_url, :twitter_url, :descripcion, :descripcion2)";
        $db->prepare($sql)->execute($campos);
    }

    echo json_encode(['ok' => true]);
}
