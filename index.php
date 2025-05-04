<?php
// Front controller para sua aplicação
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes básicas
define('ROOT_PATH', __DIR__);
define('BASE_URL', 'http://localhost/checkout');

// Carregar funções e classes essenciais
require_once 'config/database.php';
require_once 'helpers/functions.php';
require_once 'core/Router.php';

// Conectar ao banco de dados
$db = new Database();
$conn = $db->getConnection();

// Roteamento simples
$router = new Router();
$router->dispatch();
?>
