<?php
// ============================================================
// MANEJO DE SESIONES Y AUTENTICACIÓN
// php/auth.php
// ============================================================

session_start();

function estaAutenticado(): bool {
    return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
}

function requiereLogin(): void {
    if (!estaAutenticado()) {
        header('Location: ../index.php?error=no_auth');
        exit;
    }
}

function iniciarSesion(string $username, string $password): bool {
    require_once __DIR__ . '/config.php';
    $db = getDB();
    $stmt = $db->prepare("SELECT id, password_hash, nombre FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        return true;
    }
    return false;
}

function cerrarSesion(): void {
    session_destroy();
    header('Location: ../index.php');
    exit;
}
