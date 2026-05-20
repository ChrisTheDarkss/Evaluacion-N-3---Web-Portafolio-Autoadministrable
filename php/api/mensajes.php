<?php
// ============================================================
// php/api/mensajes.php
// Endpoint para gestionar mensajes del formulario de contacto
// GET          → listar todos los mensajes
// POST ver     → obtener un mensaje y marcarlo como leído
// POST eliminar→ borrar mensaje por id
// ============================================================
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/auth.php';
require_once dirname(__DIR__) . '/config.php';

requiereLogin();

$db     = getDB();
$metodo = $_SERVER['REQUEST_METHOD'];

// ---- GET: Devuelve todos los mensajes, más recientes primero ----
if ($metodo === 'GET') {
    $items = $db->query("SELECT * FROM contacto ORDER BY recibido_en DESC")->fetchAll();
    echo json_encode(['ok' => true, 'mensajes' => $items]);
    exit;
}

// ---- POST: Operaciones sobre mensajes ----
if ($metodo === 'POST') {
    $accion = trim($_POST['accion'] ?? '');

    // -- VER un mensaje: devuelve su contenido y lo marca como leído --
    if ($accion === 'ver') {
        $id = (int)($_POST['id'] ?? 0);

        // Obtener el mensaje
        $stmt = $db->prepare("SELECT * FROM contacto WHERE id = ?");
        $stmt->execute([$id]);
        $mensaje = $stmt->fetch();

        if (!$mensaje) {
            echo json_encode(['ok' => false, 'error' => 'Mensaje no encontrado.']);
            exit;
        }

        // Marcarlo como leído si aún no lo está
        if ($mensaje['leido'] == 0) {
            $db->prepare("UPDATE contacto SET leido = 1 WHERE id = ?")->execute([$id]);
        }

        echo json_encode(['ok' => true, 'mensaje' => $mensaje]);
    }

    // -- ELIMINAR mensaje --
    elseif ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $db->prepare("DELETE FROM contacto WHERE id = ?")->execute([$id]);
        echo json_encode(['ok' => true]);
    }

    else {
        echo json_encode(['ok' => false, 'error' => 'Acción no reconocida.']);
    }
}
