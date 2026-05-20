<?php
// ============================================================
// php/api/tecnologias.php
// Endpoint CRUD para tecnologías dominadas
// GET            → listar todas
// POST crear     → insertar nueva tecnología
// POST actualizar→ modificar tecnología existente
// POST eliminar  → borrar tecnología por id
// ============================================================
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/config.php';

requiereLogin();

$db     = getDB();
$metodo = $_SERVER['REQUEST_METHOD'];

// ---- GET: Devuelve todas las tecnologías ordenadas ----
if ($metodo === 'GET') {
    $items = $db->query("SELECT * FROM tecnologias ORDER BY orden ASC, id ASC")->fetchAll();
    echo json_encode(['ok' => true, 'tecnologias' => $items]);
    exit;
}

// ---- POST: Operaciones de escritura ----
if ($metodo === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    // Categorías permitidas para validar el campo
    $categorias_validas = ['Frontend', 'Backend', 'Base de Datos', 'Herramientas'];

    // -- CREAR nueva tecnología --
    if ($accion === 'crear') {
        $nombre      = trim($_POST['nombre']      ?? '');
        $nivel       = min(100, max(0, (int)($_POST['nivel'] ?? 50)));
        $categoria   = trim($_POST['categoria']   ?? 'Frontend');
        $color_desde = trim($_POST['color_desde'] ?? '#3b82f6');
        $color_hasta = trim($_POST['color_hasta'] ?? '#06b6d4');
        $orden       = (int)($_POST['orden']      ?? 0);

        if (empty($nombre)) {
            echo json_encode(['ok' => false, 'error' => 'El nombre es obligatorio.']);
            exit;
        }

        // Validar que la categoría sea una de las permitidas
        if (!in_array($categoria, $categorias_validas)) {
            $categoria = 'Frontend';
        }

        $stmt = $db->prepare(
            "INSERT INTO tecnologias (nombre, nivel, categoria, color_desde, color_hasta, orden)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$nombre, $nivel, $categoria, $color_desde, $color_hasta, $orden]);
        echo json_encode(['ok' => true, 'id' => $db->lastInsertId()]);
    }

    // -- ACTUALIZAR tecnología existente --
    elseif ($accion === 'actualizar') {
        $id          = (int)($_POST['id']          ?? 0);
        $nombre      = trim($_POST['nombre']       ?? '');
        $nivel       = min(100, max(0, (int)($_POST['nivel'] ?? 50)));
        $categoria   = trim($_POST['categoria']    ?? 'Frontend');
        $color_desde = trim($_POST['color_desde']  ?? '#3b82f6');
        $color_hasta = trim($_POST['color_hasta']  ?? '#06b6d4');
        $orden       = (int)($_POST['orden']       ?? 0);

        if (!$id || empty($nombre)) {
            echo json_encode(['ok' => false, 'error' => 'ID y nombre son obligatorios.']);
            exit;
        }

        if (!in_array($categoria, $categorias_validas)) {
            $categoria = 'Frontend';
        }

        $stmt = $db->prepare(
            "UPDATE tecnologias SET nombre=?, nivel=?, categoria=?, color_desde=?, color_hasta=?, orden=?
             WHERE id=?"
        );
        $stmt->execute([$nombre, $nivel, $categoria, $color_desde, $color_hasta, $orden, $id]);
        echo json_encode(['ok' => true]);
    }

    // -- ELIMINAR tecnología --
    elseif ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $db->prepare("DELETE FROM tecnologias WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
    }

    else {
        echo json_encode(['ok' => false, 'error' => 'Acción no reconocida.']);
    }
}
