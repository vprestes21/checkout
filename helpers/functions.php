<?php
// Funções auxiliares

// Verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirecionar
function redirect($path) {
    header('Location: ' . BASE_URL . '/' . $path);
    exit;
}

// Exibir mensagem flash
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if(isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Gerar slug único
function generateSlug($title) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    return $slug . '-' . substr(md5(time() . rand()), 0, 8);
}

// Formatar preço
function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}

// Limpar entrada
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Carregar view
function view($name, $data = []) {
    extract($data);
    require_once 'views/' . $name . '.php';
}

// Gerar token CSRF
function generateCsrfToken() {
    if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verificar token CSRF
function verifyCsrfToken($token) {
    if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}
