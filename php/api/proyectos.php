<?php
// ============================================================
// php/api/proyectos.php
// Endpoint CRUD para proyectos del portafolio
// GET             → listar todos
// POST obtener    → obtener un proyecto por id (para edición)
// POST crear      → insertar nuevo proyecto
// POST actualizar → modificar proyecto existente
// POST eliminar   → borrar proyecto por id
// ============================================================
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/config.php';

requiereLogin();

$db     = getDB();
$metodo = $_SERVER['REQUEST_METHOD'];

// ---- GET: Devuelve todos los proyectos ordenados ----
if ($metodo === 'GET') {
    $items = $db->query("SELECT * FROM proyectos ORDER BY orden ASC, id ASC")->fetchAll();
    echo json_encode(['ok' => true, 'proyectos' => $items]);
    exit;
}

// ---- POST: Operaciones de escritura ----
if ($metodo === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    // -- OBTENER un proyecto específico (para modal de edición) --
    if ($accion === 'obtener') {
        $id = (int)($_POST['id'] ?? 0);
        $proyecto = $db->prepare("SELECT * FROM proyectos WHERE id = ?");
        $proyecto->execute([$id]);
        $item = $proyecto->fetch();
        echo json_encode(['ok' => (bool)$item, 'proyecto' => $item ?: null]);
        exit;
    }

    // -- CREAR nuevo proyecto --
    if ($accion === 'crear') {
        $titulo      = trim($_POST['titulo']      ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $imagen_url  = trim($_POST['imagen_url']  ?? '');
        $demo_url    = trim($_POST['demo_url']    ?? '');
        $github_url  = trim($_POST['github_url']  ?? '');
        $tags        = trim($_POST['tags']        ?? '');
        $orden       = (int)($_POST['orden']      ?? 0);

        if (empty($titulo)) {
            echo json_encode(['ok' => false, 'error' => 'El título es obligatorio.']);
            exit;
        }

        $stmt = $db->prepare(
            "INSERT INTO proyectos (titulo, descripcion, imagen_url, demo_url, github_url, tags, orden)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$titulo, $descripcion, $imagen_url, $demo_url, $github_url, $tags, $orden]);
        echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
    }

    // -- ACTUALIZAR proyecto existente --
    elseif ($accion === 'actualizar') {
        $id          = (int)($_POST['id']          ?? 0);
        $titulo      = trim($_POST['titulo']       ?? '');
        $descripcion = trim($_POST['descripcion']  ?? '');
        $imagen_url  = trim($_POST['imagen_url']   ?? '');
        $demo_url    = trim($_POST['demo_url']     ?? '');
        $github_url  = trim($_POST['github_url']   ?? '');
        $tags        = trim($_POST['tags']         ?? '');
        $orden       = (int)($_POST['orden']       ?? 0);

        if (!$id || empty($titulo)) {
            echo json_encode(['ok' => false, 'error' => 'ID y título son obligatorios.']);
            exit;
        }

        $stmt = $db->prepare(
            "UPDATE proyectos SET titulo=?, descripcion=?, imagen_url=?, demo_url=?, github_url=?, tags=?, orden=?
             WHERE id=?"
        );
        $stmt->execute([$titulo, $descripcion, $imagen_url, $demo_url, $github_url, $tags, $orden, $id]);
        echo json_encode(['ok' => true]);
    }

    // -- ELIMINAR proyecto --
    elseif ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $db->prepare("DELETE FROM proyectos WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
    }

    else {
        echo json_encode(['ok' => false, 'error' => 'Acción no reconocida.']);
    }
}
