<?php
// ============================================================
// php/login.php — Maneja el inicio de sesión
// ============================================================
header('Content-Type: application/json');
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(['ok' => false, 'error' => 'Ingresa usuario y contraseña.']);
    exit;
}

if (iniciarSesion($username, $password)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Usuario o contraseña incorrectos.']);
}
