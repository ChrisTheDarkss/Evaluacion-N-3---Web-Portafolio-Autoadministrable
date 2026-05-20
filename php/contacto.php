<?php
// ============================================================
// php/contacto.php — Recibe y guarda mensajes del formulario
// ============================================================
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$nombre  = trim($_POST['nombre']  ?? '');
$email   = trim($_POST['email']   ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Validaciones
if (empty($nombre) || empty($email) || empty($mensaje)) {
    echo json_encode(['ok' => false, 'error' => 'Completa todos los campos requeridos.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'error' => 'El correo electrónico no es válido.']);
    exit;
}
if (strlen($mensaje) < 10) {
    echo json_encode(['ok' => false, 'error' => 'El mensaje es demasiado corto.']);
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare(
        "INSERT INTO contacto (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$nombre, $email, $asunto, $mensaje]);
    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error al guardar el mensaje.']);
}
