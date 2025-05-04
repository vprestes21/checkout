<?php
// Script para corrigir problemas nas tabelas do banco de dados

require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die('Não foi possível conectar ao banco de dados.');
}

try {
    // 1. Modificar a coluna payment_methods para TEXT
    $alterQuery = "ALTER TABLE products MODIFY COLUMN payment_methods TEXT NOT NULL";
    $conn->exec($alterQuery);
    
    echo "<h1>Correção aplicada com sucesso!</h1>";
    echo "<p>A tabela 'products' foi modificada para resolver o problema com o campo 'payment_methods'.</p>";
    echo "<p>Agora você pode <a href='index.php'>voltar ao sistema</a> e continuar usando normalmente.</p>";
    
} catch (PDOException $e) {
    echo "<h1>Erro ao aplicar correção</h1>";
    echo "<p>Detalhes do erro: " . $e->getMessage() . "</p>";
}
?>
