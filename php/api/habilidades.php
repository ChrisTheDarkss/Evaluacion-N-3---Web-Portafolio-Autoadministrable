<?php
// ============================================================
// php/api/habilidades.php
// Endpoint CRUD para habilidades/herramientas
// GET            → listar todas
// POST crear     → insertar nueva habilidad
// POST actualizar→ modificar habilidad existente
// POST eliminar  → borrar habilidad por id
// ============================================================
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/config.php';

requiereLogin();

$db     = getDB();
$metodo = $_SERVER['REQUEST_METHOD'];

// ---- GET: Devuelve todas las habilidades ordenadas ----
if ($metodo === 'GET') {
    $items = $db->query("SELECT * FROM habilidades ORDER BY orden ASC, id ASC")->fetchAll();
    echo json_encode(['ok' => true, 'habilidades' => $items]);
    exit;
}

// ---- POST: Operaciones de escritura ----
if ($metodo === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    // -- CREAR nueva habilidad --
    if ($accion === 'crear') {
        $nombre      = trim($_POST['nombre']      ?? '');
        $icono       = trim($_POST['icono']       ?? 'bi bi-code-slash');
        $color_desde = trim($_POST['color_desde'] ?? '#3b82f6');
        $color_hasta = trim($_POST['color_hasta'] ?? '#8b5cf6');
        $orden       = (int)($_POST['orden']      ?? 0);

        if (empty($nombre)) {
            echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio.']);
            exit;
        }

        $stmt = $db->prepare(
            "INSERT INTO habilidades (nombre, icono, color_desde, color_hasta, orden)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nombre, $icono, $color_desde, $color_hasta, $orden]);
        echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
    }

    // -- ACTUALIZAR habilidad existente --
    elseif ($accion === 'actualizar') {
        $id          = (int)($_POST['id']          ?? 0);
        $nombre      = trim($_POST['nombre']       ?? '');
        $icono       = trim($_POST['icono']        ?? 'bi bi-code-slash');
        $color_desde = trim($_POST['color_desde']  ?? '#3b82f6');
        $color_hasta = trim($_POST['color_hasta']  ?? '#8b5cf6');
        $orden       = (int)($_POST['orden']       ?? 0);

        if (!$id || empty($nombre)) {
            echo json_encode(['ok' => false, 'error' => 'ID y nombre son obligatorios.']);
            exit;
        }

        $stmt = $db->prepare(
            "UPDATE habilidades SET nombre=?, icono=?, color_desde=?, color_hasta=?, orden=?
             WHERE id=?"
        );
        $stmt->execute([$nombre, $icono, $color_desde, $color_hasta, $orden, $id]);
        echo json_encode(['ok' => true]);
    }

    // -- ELIMINAR habilidad --
    elseif ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $db->prepare("DELETE FROM habilidades WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
    }

    else {
        echo json_encode(['ok' => false, 'error' => 'Acción no reconocida.']);
    }
}
